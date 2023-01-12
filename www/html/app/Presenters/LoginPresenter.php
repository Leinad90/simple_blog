<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Facade\Authenticator;
use App\Model\User;
use App\Model\UserExistsException;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Utils\ArrayHash;
use Nette;


final class LoginPresenter extends Nette\Application\UI\Presenter
{

    public function __construct(
        private readonly User $userModel,
        private readonly Nette\Security\Passwords $passwords
    )
    {
        parent::__construct();
    }

    public function actionLogout() : never
    {
        $this->getUser()->logout(true);
        $this->flashMessage('Odhlášen');
        $this->redirect('Homepage:default');
    }

    public function login(Form $form, ArrayHash $formData) : void
    {
        try {
            $this->getUser()->login($formData->name, $formData->password);
            $this->flashMessage('Uspěšně přihlášen');
            $this->redirect('Homepage:default');
        } catch (AuthenticationException $e) {
            $form->addError($e->getMessage());
        }
    }

    public function register(Form $form, ArrayHash $formData) : void
    {
        try {
            $this->userModel->save($formData->name, $this->passwords->hash($formData->password));
            $this->flashMessage('Uživatel zaregistrován');
            $this->login($form, $formData);
        } catch (UserExistsException $e) {
            $form['name']->addError('Uživatel existuje');
        }
    }

    public function createComponentLoginForm(): Form
    {
        $form = new Form();
        $form->addText('name','Jméno')->setRequired();
        $form->addText('password','Heslo')->setRequired();
        $form->addSubmit('login', 'Přihlásit');
        $form->onSuccess[] = [$this, 'login'];
        return $form;
    }

    public function createComponentRegistrationForm(): Form
    {
        $form = new Form();
        $form->addText('name','Jméno')->setRequired();
        $form->addPassword('password','Heslo')->setRequired();
        $form->addPassword('password2','Ověření hesla')->setRequired()->addRule(Form::EQUAL,'Hesla se liší', $form['password']);
        $form->addSubmit('register', 'Registrovat');
        $form->onSuccess[] = [$this, 'register'];
        return $form;
    }

}

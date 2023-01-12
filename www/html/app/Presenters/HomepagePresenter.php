<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Model\Article;
use App\Model\Enum\ArticleOrder;
use Nette;


final class HomepagePresenter extends Nette\Application\UI\Presenter
{
    #[Nette\Application\Attributes\Persistent]
    public int $page = 1;


    public ArticleOrder $order = ArticleOrder::posted;


    public function __construct(private Article $article)
    {
        parent::__construct();
    }

    public function actionDefault(?int $page = null, ?string $order = null) : void
    {
        if($page!==null) {
            $this->page = $page;
        }
        if($order!==null) {
            $this->order = ArticleOrder::from($order);
        }
        $this->template->order = ArticleOrder::cases();
        $articlesCount = $this->article->getCount($this->getUser());
        $paginator = new Nette\Utils\Paginator;
        $paginator->setItemCount($articlesCount);
        $paginator->setItemsPerPage(10);
        $paginator->setPage($page);
        $this->template->articles = $this->article->getAll($paginator, $this->order, $this->getUser());
        $this->template->page = $page;
        $this->template->lastPage = $paginator->getLastPage();
    }

    public function actionRank(int $id, int $value) : void
    {
        $this->article->rank($id, $value, $this->getUser()->getId());
        if($this->isAjax()) {
            $this->template->articles[$id] = $this->article->getOne($id);
            $this->redrawControl('article_'.$id);
            $this->sendPayload();
        } else {
            $this->redirect('default');
        }
    }

    public function actionDetail(int $id) : void
    {
        $this->template->article = $article = $this->article->getOne($id);
        if($article===null) {
            $this->error('Page not found');
        }
        $this->template->title = $article->title;
    }


}

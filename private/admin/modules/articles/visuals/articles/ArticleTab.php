<?php
require_once CMS_ROOT . "/modules/articles/visuals/articles/ArticleEditor.php";
require_once CMS_ROOT . "/modules/articles/visuals/articles/ArticlesList.php";
require_once CMS_ROOT . "/modules/articles/visuals/articles/ArticlesSearch.php";

class ArticleTab extends Visual {

    private ?Article $currentArticle;
    private ArticleRequestHandler $articleRequestHandler;

    public function __construct(ArticleRequestHandler $articleRequestHandler) {
        parent::__construct();
        $this->articleRequestHandler = $articleRequestHandler;
        $this->currentArticle = $articleRequestHandler->getCurrentArticle();
    }

    public function getTemplateFilename(): string {
        return "modules/articles/articles/root.tpl";
    }

    public function load(): void {
        $this->assign("search", $this->renderArticlesSearchPanel());
        if (!is_null($this->currentArticle)) {
            $this->assign("editor", $this->renderArticleEditor());
        } else {
            $this->assign("list", $this->renderArticlesList());
        }
    }

    private function renderArticlesSearchPanel(): string {
        return (new ArticlesSearch($this->articleRequestHandler))->render();
    }

    private function renderArticlesList(): string {
        return (new ArticlesList($this->articleRequestHandler))->render();
    }

    private function renderArticleEditor(): string {
        return (new ArticleEditor($this->currentArticle))->render();
    }

}

?>

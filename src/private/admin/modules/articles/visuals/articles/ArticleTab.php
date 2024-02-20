<?php

namespace Obcato\Core;

use Obcato\ComponentApi\TemplateEngine;

class ArticleTab extends Obcato\ComponentApi\Visual {

    private ?Article $currentArticle;
    private ArticleRequestHandler $articleRequestHandler;

    public function __construct(TemplateEngine $templateEngine, ArticleRequestHandler $articleRequestHandler) {
        parent::__construct($templateEngine);
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
        return (new ArticlesSearch($this->getTemplateEngine(), $this->articleRequestHandler))->render();
    }

    private function renderArticlesList(): string {
        return (new ArticlesList($this->getTemplateEngine(), $this->articleRequestHandler))->render();
    }

    private function renderArticleEditor(): string {
        return (new ArticleEditor($this->getTemplateEngine(), $this->currentArticle))->render();
    }

}
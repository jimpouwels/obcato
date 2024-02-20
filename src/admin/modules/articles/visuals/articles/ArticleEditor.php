<?php

namespace Obcato\Core\admin\modules\articles\visuals\articles;

use Obcato\ComponentApi\TemplateEngine;
use Obcato\ComponentApi\Visual;
use Obcato\Core\admin\database\dao\ArticleDao;
use Obcato\Core\admin\database\dao\ArticleDaoMysql;
use Obcato\Core\admin\modules\articles\model\Article;
use Obcato\Core\admin\view\views\ElementContainer;
use Obcato\Core\admin\view\views\LinkEditor;
use Obcato\Core\admin\view\views\TermSelector;
use const Obcato\Core\admin\ELEMENT_HOLDER_FORM_ID;

class ArticleEditor extends Visual {

    private Article $currentArticle;
    private ArticleDao $articleDao;

    public function __construct(TemplateEngine $templateEngine, $currentArticle) {
        parent::__construct($templateEngine);
        $this->currentArticle = $currentArticle;
        $this->articleDao = ArticleDaoMysql::getInstance();
    }

    public function getTemplateFilename(): string {
        return "modules/articles/articles/editor.tpl";
    }

    public function load(): void {
        $this->assign("article_id", $this->currentArticle->getId());
        $this->assign("article_metadata", $this->renderArticleMetaDataPanel());
        $this->assign("element_container", $this->renderElementContainer());
        $this->assign("link_editor", $this->renderLinkEditor());
        $this->assign("term_selector", $this->renderTermSelector());
        $this->assign("element_holder_form_id", ELEMENT_HOLDER_FORM_ID);
    }

    private function renderArticleMetaDataPanel(): string {
        return (new ArticleMetadataEditor($this->getTemplateEngine(), $this->currentArticle))->render();
    }

    private function renderElementContainer(): string {
        return (new ElementContainer($this->getTemplateEngine(), $this->currentArticle->getElements()))->render();
    }

    private function renderLinkEditor(): string {
        return (new LinkEditor($this->getTemplateEngine(), $this->currentArticle->getLinks()))->render();
    }

    private function renderTermSelector(): string {
        return (new TermSelector($this->getTemplateEngine(), $this->articleDao->getTermsForArticle($this->currentArticle->getId()), $this->currentArticle->getId()))->render();
    }

}
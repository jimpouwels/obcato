<?php
require_once CMS_ROOT . "/view/views/ElementContainer.php";
require_once CMS_ROOT . "/view/views/LinkEditor.php";
require_once CMS_ROOT . "/view/views/TermSelector.php";
require_once CMS_ROOT . "/view/views/ImagePicker.php";
require_once CMS_ROOT . "/database/dao/ArticleDaoMysql.php";
require_once CMS_ROOT . '/modules/articles/visuals/articles/ArticleMetadataEditor.php';

class ArticleEditor extends Obcato\ComponentApi\Visual {

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
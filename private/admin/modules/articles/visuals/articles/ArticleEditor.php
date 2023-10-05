<?php
require_once CMS_ROOT . "/view/views/ElementContainer.php";
require_once CMS_ROOT . "/view/views/LinkEditor.php";
require_once CMS_ROOT . "/view/views/TermSelector.php";
require_once CMS_ROOT . "/view/views/ImagePicker.php";
require_once CMS_ROOT . "/database/dao/ArticleDaoMysql.php";
require_once CMS_ROOT . '/modules/articles/visuals/articles/ArticleMetadataEditor.php';

class ArticleEditor extends Visual {

    private Article $_current_article;
    private ArticleDao $articleDao;

    public function __construct($current_article) {
        parent::__construct();
        $this->_current_article = $current_article;
        $this->articleDao = ArticleDaoMysql::getInstance();
    }

    public function getTemplateFilename(): string {
        return "modules/articles/articles/editor.tpl";
    }

    public function load(): void {
        $this->assign("article_id", $this->_current_article->getId());
        $this->assign("article_metadata", $this->renderArticleMetaDataPanel());
        $this->assign("element_container", $this->renderElementContainer());
        $this->assign("link_editor", $this->renderLinkEditor());
        $this->assign("term_selector", $this->renderTermSelector());
        $this->assign("element_holder_form_id", ELEMENT_HOLDER_FORM_ID);
    }

    private function renderArticleMetaDataPanel(): string {
        $metadata_panel = new ArticleMetadataEditor($this->_current_article);
        return $metadata_panel->render();
    }

    private function renderElementContainer(): string {
        $element_container = new ElementContainer($this->_current_article->getElements());
        return $element_container->render();
    }

    private function renderLinkEditor(): string {
        $link_editor = new LinkEditor($this->_current_article->getLinks());
        return $link_editor->render();
    }

    private function renderTermSelector(): string {
        $term_selector = new TermSelector($this->articleDao->getTermsForArticle($this->_current_article->getId()), $this->_current_article->getId());
        return $term_selector->render();
    }

}

?>

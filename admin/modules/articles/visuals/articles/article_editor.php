<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/element_container.php";
    require_once CMS_ROOT . "view/views/link_editor.php";
    require_once CMS_ROOT . "view/views/term_selector.php";
    require_once CMS_ROOT . "view/views/image_picker.php";
    require_once CMS_ROOT . "database/dao/article_dao.php";
    require_once CMS_ROOT . 'modules/articles/visuals/articles/article_metadata_editor.php';

    class ArticleEditor extends Visual {

        private Article $_current_article;
        private ArticleDao $_article_dao;

        public function __construct($current_article) {
            parent::__construct();
            $this->_current_article = $current_article;
            $this->_article_dao = ArticleDao::getInstance();
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

        private function getDateValue(string $date): string {
            return DateUtility::mysqlDateToString($date, '-');
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
            $term_selector = new TermSelector($this->_current_article->getTerms(), $this->_current_article->getId());
            return $term_selector->render();
        }

        private function getTargetPageOptions(): array {
            $target_page_options = array();
            array_push($target_page_options, array("name" => "&gt; Selecteer", "value" => ""));

            $all_target_pages = $this->_article_dao->getTargetPages();
            foreach ($all_target_pages as $article_target_page) {
                array_push($target_page_options, array("name" => $article_target_page->getTitle(), "value" => $article_target_page->getId()));
            }
            return $target_page_options;
        }

    }

?>

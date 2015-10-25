<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/element_container.php";
    require_once CMS_ROOT . "view/views/link_editor.php";
    require_once CMS_ROOT . "view/views/term_selector.php";
    require_once CMS_ROOT . "view/views/image_picker.php";
    require_once CMS_ROOT . "database/dao/article_dao.php";
    require_once CMS_ROOT . 'modules/articles/visuals/articles/article_metadata_editor.php';

    class ArticleEditor extends Visual {

        private static $ARTICLE_EDITOR_TEMPLATE = "articles/articles/editor.tpl";
        private static $ARTICLE_METADATA_TEMPLATE = "articles/articles/metadata.tpl";

        private $_template_engine;
        private $_current_article;
        private $_article_dao;

        public function __construct($current_article) {
            $this->_current_article = $current_article;
            $this->_template_engine = TemplateEngine::getInstance();
            $this->_article_dao = ArticleDao::getInstance();
        }

        public function render() {
            $this->_template_engine->assign("article_metadata", $this->renderArticleMetaDataPanel());
            $this->_template_engine->assign("element_container", $this->renderElementContainer());
            $this->_template_engine->assign("link_editor", $this->renderLinkEditor());
            $this->_template_engine->assign("term_selector", $this->renderTermSelector());

            return $this->_template_engine->fetch("modules/" . self::$ARTICLE_EDITOR_TEMPLATE);
        }


        private function renderArticleMetaDataPanel() {
            $metadata_panel = new ArticleMetadataEditor($this->_current_article);
            return $metadata_panel->render();
        }

        private function getDateValue($date) {
            return DateUtility::mysqlDateToString($date, '-');
        }

        private function renderElementContainer() {
            $element_container = new ElementContainer($this->_current_article->getElements());
            return $element_container->render();
        }

        private function renderLinkEditor() {
            $link_editor = new LinkEditor($this->_current_article->getLinks());
            return $link_editor->render();
        }

        private function renderTermSelector() {
            $term_selector = new TermSelector($this->_current_article->getTerms(), $this->_current_article->getId());
            return $term_selector->render();
        }

        private function getTargetPageOptions() {
            $target_page_options = array();
            array_push($target_page_options, array("name" => "&gt; Selecteer", "value" => ""));

            $all_target_pages = $this->_article_dao->getTargetPages();
            foreach ($all_target_pages as $article_target_page) {
                array_push($target_page_options, array("name" => $article_target_page->getTitle(), "value" => $article_target_page->getId()));
            }
            return $target_page_options;
        }

        private function assignElementHolderFormIds() {
            $this->_template_engine->assign("add_element_form_id", ADD_ELEMENT_FORM_ID);
            $this->_template_engine->assign("edit_element_holder_id", EDIT_ELEMENT_HOLDER_ID);
            $this->_template_engine->assign("element_holder_form_id", ELEMENT_HOLDER_FORM_ID);
            $this->_template_engine->assign("action_form_id", ACTION_FORM_ID);
            $this->_template_engine->assign("delete_element_form_id", DELETE_ELEMENT_FORM_ID);
            $this->_template_engine->assign("element_order_id", ELEMENT_ORDER_ID);
        }

    }

?>

<?php
    
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/element_container.php";
    require_once CMS_ROOT . "view/views/link_editor.php";
    require_once CMS_ROOT . "view/views/term_selector.php";
    require_once CMS_ROOT . "view/views/image_picker.php";
    require_once CMS_ROOT . "database/dao/article_dao.php";
    
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
            $this->assignElementHolderFormIds();
            $this->_template_engine->assign("article_metadata", $this->renderArticleMetaData());
            $this->_template_engine->assign("element_container", $this->renderElementContainer());
            $this->_template_engine->assign("link_editor", $this->renderLinkEditor());
            $this->_template_engine->assign("term_selector", $this->renderTermSelector());
            $this->_template_engine->assign("id", $this->_current_article->getId());
            
            return $this->_template_engine->fetch("modules/" . self::$ARTICLE_EDITOR_TEMPLATE);
        }
        
        
        private function renderArticleMetaData() {
            $title_field = new TextField("article_title", $this->getTextResource('article_editor_title_label'), $this->_current_article->getTitle(), true, false, null);
            $description_field = new TextArea("article_description", $this->getTextResource('article_editor_description_label'), $this->_current_article->getDescription(), 200, 8, false, true, null);
            $published_field = new SingleCheckbox("article_published", $this->getTextResource('article_editor_published_label'), $this->_current_article->isPublished(), false, "");
            $publication_date_field = new DateField("publication_date", $this->getTextResource('article_editor_publication_date_label'), $this->getDateValue($this->_current_article->getPublicationDate()), true, null);
            $sort_date_field = new DateField("sort_date", $this->getTextResource('article_editor_sort_date_label'), $this->getDateValue($this->_current_article->getSortDate($this->_current_article->getSortDate())), true, null);
            $target_pages_field = new Pulldown("article_target_page", $this->getTextResource('article_editor_target_page_label'), $this->_current_article->getTargetPageId(), $this->getTargetPageOptions(), false, null);            
            $image_picker_field = new ImagePicker($this->getTextResource('article_editor_image_label'), $this->_current_article->getImageId(), "article_image_ref_" . $this->_current_article->getId(), "Selecteer afbeelding", "update_element_holder", null);
            $image_delete_button = new Button("delete_lead_image", $this->getTextResource('article_editor_delete_image_button_label'), null);
            
            $this->_template_engine->assign("title_field", $title_field->render());
            $this->_template_engine->assign("description_field", $description_field->render());
            $this->_template_engine->assign("published_field", $published_field->render());
            $this->_template_engine->assign("publication_date_field", $publication_date_field->render());
            $this->_template_engine->assign("sort_date_field", $sort_date_field->render());
            $this->_template_engine->assign("target_pages_field", $target_pages_field->render());
            $this->_template_engine->assign("image_picker_field", $image_picker_field->render());
            $this->_template_engine->assign("lead_image_id", $this->_current_article->getImageId());
            $this->_template_engine->assign("delete_lead_image_button", $image_delete_button->render());
                        
            return $this->_template_engine->fetch("modules/" . self::$ARTICLE_METADATA_TEMPLATE);
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
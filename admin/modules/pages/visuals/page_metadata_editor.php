<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'friendly_urls/friendly_url_manager.php';

    class MetadataEditor extends Panel {

        private static $PAGE_METADATA_TEMPLATE = "pages/metadata.tpl";

        private $_template_engine;
        private $_current_page;
        private $_friendly_url_manager;

        public function __construct($current_page) {
            parent::__construct($this->getTextResource('edit_metadata_title'), "page_metadata_editor");
            $this->_current_page = $current_page;
            $this->_template_engine = TemplateEngine::getInstance();
            $this->_friendly_url_manager = FriendlyUrlManager::getInstance();
        }

        public function render() {
            return parent::render();
        }

        public function renderPanelContent() {
            $title_field = new TextField("page_title", $this->getTextResource('pages_edit_metadata_title_field_label'), $this->_current_page->getTitle(), true, false, null);
            $navigation_title_field = new TextField("navigation_title", $this->getTextResource('pages_edit_metadata_navigation_title_field_label'), $this->_current_page->getNavigationTitle(), true, false, null);
            $url_field = new ReadonlyTextField('friendly_url', $this->getTextResource('pages_edit_metadata_friendly_url_label'), $this->_friendly_url_manager->getFriendlyUrlForElementHolder($this->_current_page), '');
            $description_field = new TextArea("description", $this->getTextResource('pages_edit_metadata_description_field_label'), $this->_current_page->getDescription(), false, true, null);
            $published_field = new SingleCheckbox("published", $this->getTextResource('pages_edit_metadata_ispublished_field_label'), $this->_current_page->isPublished(), false, "");
            $show_in_navigation_field = new SingleCheckbox("show_in_navigation", $this->getTextResource('pages_edit_metadata_showinnavigation_field_label'), $this->_current_page->getShowInNavigation(), false, "");
            $template_picker_field = new TemplatePicker("page_template", $this->getTextResource('pages_edit_metadata_template_field_label'), false, "", $this->_current_page->getTemplate(), $this->_current_page->getScope());

            $this->_template_engine->assign("current_page_id", $this->_current_page->getId());
            $this->_template_engine->assign("page_title_field", $title_field->render());
            $this->_template_engine->assign("navigation_title_field", $navigation_title_field->render());
            $this->_template_engine->assign('url_field', $url_field->render());
            $this->_template_engine->assign("description_field", $description_field->render());
            $this->_template_engine->assign("published_field", $published_field->render());
            $this->_template_engine->assign("show_in_navigation_field", $show_in_navigation_field->render());
            $this->_template_engine->assign("template_picker_field", $template_picker_field->render());
            $this->assignElementHolderFormIds();
            return $this->_template_engine->fetch("modules/" . self::$PAGE_METADATA_TEMPLATE);
        }

        private function assignElementHolderFormIds() {
            $this->_template_engine->assign("add_element_form_id", ADD_ELEMENT_FORM_ID);
            $this->_template_engine->assign("edit_element_holder_id", EDIT_ELEMENT_HOLDER_ID);
            $this->_template_engine->assign("action_form_id", ACTION_FORM_ID);
            $this->_template_engine->assign("delete_element_form_id", DELETE_ELEMENT_FORM_ID);
            $this->_template_engine->assign("element_order_id", ELEMENT_ORDER_ID);
            $this->_template_engine->assign("element_holder_form_id", ELEMENT_HOLDER_FORM_ID);
        }
    }

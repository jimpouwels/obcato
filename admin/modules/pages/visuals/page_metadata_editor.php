<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'friendly_urls/friendly_url_manager.php';

    class MetadataEditor extends Panel {

        private Page $_current_page;
        private FriendlyUrlManager $_friendly_url_manager;

        public function __construct(Page $current_page) {
            parent::__construct($this->getTextResource('edit_metadata_title'), "page_metadata_editor");
            $this->_current_page = $current_page;
            $this->_friendly_url_manager = FriendlyUrlManager::getInstance();
        }

        public function getPanelContentTemplate(): string {
            return "modules/pages/metadata.tpl";
        }

        public function loadPanelContent(Smarty_Internal_Data $data): void {
            $title_field = new TextField("page_title", $this->getTextResource('pages_edit_metadata_title_field_label'), $this->_current_page->getTitle(), true, false, null);
            $navigation_title_field = new TextField("navigation_title", $this->getTextResource('pages_edit_metadata_navigation_title_field_label'), $this->_current_page->getNavigationTitle(), true, false, null);
            $url_field = new ReadonlyTextField('friendly_url', $this->getTextResource('pages_edit_metadata_friendly_url_label'), $this->_friendly_url_manager->getFriendlyUrlForElementHolder($this->_current_page), '');
            $description_field = new TextArea("description", $this->getTextResource('pages_edit_metadata_description_field_label'), $this->_current_page->getDescription(), false, true, null);
            $published_field = new SingleCheckbox("published", $this->getTextResource('pages_edit_metadata_ispublished_field_label'), $this->_current_page->isPublished(), false, "");
            $show_in_navigation_field = new SingleCheckbox("show_in_navigation", $this->getTextResource('pages_edit_metadata_showinnavigation_field_label'), $this->_current_page->getShowInNavigation(), false, "");
            $template_picker_field = new TemplatePicker("page_template", $this->getTextResource('pages_edit_metadata_template_field_label'), false, "", $this->_current_page->getTemplate(), $this->_current_page->getScope());

            $data->assign("current_page_id", $this->_current_page->getId());
            $data->assign("page_title_field", $title_field->render());
            $data->assign("navigation_title_field", $navigation_title_field->render());
            $data->assign('url_field', $url_field->render());
            $data->assign("description_field", $description_field->render());
            $data->assign("published_field", $published_field->render());
            $data->assign("show_in_navigation_field", $show_in_navigation_field->render());
            $data->assign("template_picker_field", $template_picker_field->render());
            $this->assignElementHolderFormIds($data);
        }

        private function assignElementHolderFormIds($data) {
            $data->assign("add_element_form_id", ADD_ELEMENT_FORM_ID);
            $data->assign("edit_element_holder_id", EDIT_ELEMENT_HOLDER_ID);
            $data->assign("action_form_id", ACTION_FORM_ID);
            $data->assign("delete_element_form_id", DELETE_ELEMENT_FORM_ID);
            $data->assign("element_order_id", ELEMENT_ORDER_ID);
            $data->assign("element_holder_form_id", ELEMENT_HOLDER_FORM_ID);
        }
    }

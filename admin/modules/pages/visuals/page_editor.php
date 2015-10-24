<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/form_template_picker.php";
    require_once CMS_ROOT . "view/views/element_container.php";
    require_once CMS_ROOT . "view/views/link_editor.php";
    require_once CMS_ROOT . "view/views/block_selector.php";
    require_once CMS_ROOT . 'friendly_urls/friendly_url_manager.php';

    class PageEditor extends Visual {

        private static $PAGE_EDITOR_TEMPLATE = "pages/editor.tpl";
        private static $PAGE_METADATA_TEMPLATE = "pages/metadata.tpl";

        private $_template_engine;
        private $_current_page;
        private $_friendly_url_manager;

        public function __construct($current_page) {
            $this->_current_page = $current_page;
            $this->_template_engine = TemplateEngine::getInstance();
            $this->_friendly_url_manager = new FriendlyUrlManager();
        }

        public function render() {
            $this->assignElementHolderFormIds();
            $this->_template_engine->assign("page_metadata", $this->renderPageMetaData());
            $this->_template_engine->assign("element_container", $this->renderElementContainer());
            $this->_template_engine->assign("link_editor", $this->renderLinkEditor());
            $this->_template_engine->assign("block_selector", $this->renderBlockSelector());

            $this->_template_engine->assign("id", $this->_current_page->getId());
            return $this->_template_engine->fetch("modules/" . self::$PAGE_EDITOR_TEMPLATE);
        }

        private function renderPageMetaData() {
            $title_field = new TextField("page_title", $this->getTextResource('title_field_label'), $this->_current_page->getTitle(), true, false, null);
            $navigation_title_field = new TextField("navigation_title", $this->getTextResource('navigation_title_field_label'), $this->_current_page->getNavigationTitle(), true, false, null);
            $url_field = new ReadonlyTextField('friendly_url', $this->getTextResource('friendly_url_label'), $this->_friendly_url_manager->getFriendlyUrlForElementHolder($this->_current_page), '');
            $description_field = new TextArea("description", $this->getTextResource('description_field_label'), $this->_current_page->getDescription(), 200, 8, false, true, null);
            $published_field = new SingleCheckbox("published", $this->getTextResource('ispublished_field_label'), $this->_current_page->isPublished(), false, "");
            $show_in_navigation_field = new SingleCheckbox("show_in_navigation", $this->getTextResource('showinnavigation_field_label'), $this->_current_page->getShowInNavigation(), false, "");
            $template_picker_field = new TemplatePicker("page_template", $this->getTextResource('template_field_label'), false, "", $this->_current_page->getTemplate(), $this->_current_page->getScope());

            $this->_template_engine->assign("page_title_field", $title_field->render());
            $this->_template_engine->assign("navigation_title_field", $navigation_title_field->render());
            $this->_template_engine->assign('url_field', $url_field->render());
            $this->_template_engine->assign("description_field", $description_field->render());
            $this->_template_engine->assign("published_field", $published_field->render());
            $this->_template_engine->assign("show_in_navigation_field", $show_in_navigation_field->render());
            $this->_template_engine->assign("template_picker_field", $template_picker_field->render());
            return $this->_template_engine->fetch("modules/" . self::$PAGE_METADATA_TEMPLATE);
        }

        private function renderElementContainer() {
            $element_container = new ElementContainer($this->_current_page->getElements());
            return $element_container->render();
        }

        private function renderLinkEditor() {
            $link_editor = new LinkEditor($this->_current_page->getLinks());
            return $link_editor->render();
        }

        private function renderBlockSelector() {
            $block_selector = new BlockSelector($this->_current_page->getBlocks(), $this->_current_page->getId());
            return $block_selector->render();
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

?>

<?php

namespace {
    defined('_ACCESS') or die;

    require_once CMS_ROOT . '/view/views/Visual.php';
    require_once CMS_ROOT . '/modules/webforms/visuals/webforms/fields/WebformButtonVisual.php';
    require_once CMS_ROOT . '/modules/webforms/visuals/webforms/fields/WebformTextfieldVisual.php';
    require_once CMS_ROOT . '/modules/webforms/visuals/webforms/fields/WebformTextareaVisual.php';
    require_once CMS_ROOT . '/modules/webforms/form/WebformButtonForm.php';
    require_once CMS_ROOT . '/modules/webforms/form/WebformTextFieldForm.php';
    require_once CMS_ROOT . '/modules/webforms/form/WebformTextAreaForm.php';
    require_once CMS_ROOT . '/modules/webforms/model/WebformTextField.php';
    require_once CMS_ROOT . '/modules/webforms/model/WebformTextArea.php';
    require_once CMS_ROOT . '/modules/webforms/model/WebformDropdown.php';
    require_once CMS_ROOT . '/modules/webforms/model/WebformButton.php';
    require_once CMS_ROOT . '/frontend/FormTextfieldVisual.php';
    require_once CMS_ROOT . '/frontend/FormTextAreaVisual.php';
    require_once CMS_ROOT . '/frontend/FormDropdownVisual.php';
    require_once CMS_ROOT . '/frontend/FormButtonVisual.php';

    class WebformItemFactory {

        private array $_types = array();
        private static ?WebformItemFactory $_instance = null;

        private function __construct() {
            $this->addType(WebformTextField::$TYPE, "WebformTextfieldVisual", "WebformTextFieldForm", "FormTextFieldVisual");
            $this->addType(WebFormTextArea::$TYPE, "WebformTextAreaVisual", "WebformTextAreaForm", "FormTextAreaVisual");
            $this->addType(WebFormDropDown::$TYPE, "WebformDropDownVisual", "WebformDropDownForm", "FormDropDownVisual");
            $this->addType(WebFormButton::$TYPE, "WebformButtonVisual", "WebformButtonForm", "FormButtonVisual");
        }

        public static function getInstance(): WebformItemFactory {
            if (!self::$_instance) {
                self::$_instance = new WebformItemFactory();
            }
            return self::$_instance;
        }

        public function getBackendVisualFor(WebFormItem $webform_item): WebformItemVisual {
            $backend_visual_classname = $this->getFormItemType($webform_item->getType())->getBackendVisualClassname();
            return new $backend_visual_classname($webform_item);
        }

        public function getBackendFormFor(WebFormItem $webform_item): WebformItemForm {
            $backend_form_classname = $this->getFormItemType($webform_item->getType())->getBackendFormClassname();
            return new $backend_form_classname($webform_item);
        }

        public function getFrontendVisualFor(WebForm $webform, WebFormItem $webform_item, Page $page, ?Article $article): FormItemVisual {
            $frontend_form_classname = $this->getFormItemType($webform_item->getType())->getFrontendVisualClassname();
            return new $frontend_form_classname($page, $article, $webform, $webform_item);
        }

        private function getFormItemType(string $type_to_find): WebFormItemFactory\FormItemType {
            $found_type = null;
            foreach ($this->_types as $type) {
                if ($type->getTypeName() == $type_to_find) {
                    $found_type = $type;
                }
            }
            return $found_type;
        }

        private function addType(string $type_name, string $backend_visual_classname, string $backend_form_classname, string $frontend_visual_classname): void {
            $this->_types[] = new WebFormItemFactory\FormItemType($type_name, $backend_visual_classname, $backend_form_classname, $frontend_visual_classname);
        }
    }
}

namespace WebFormItemFactory {
    class FormItemType {
        private string $_type_name;
        private string $_backend_visual_classname;
        private string $_backend_form_classname;
        private string $_frontend_visual_classname;

        public function __construct(string $type_name, string $_backend_visual_classname, string $backend_form_classname, string $frontend_visual_classname) {
            $this->_type_name = $type_name;
            $this->_backend_visual_classname = $_backend_visual_classname;
            $this->_backend_form_classname = $backend_form_classname;
            $this->_frontend_visual_classname = $frontend_visual_classname;
        }

        public function getTypeName(): string {
            return $this->_type_name;
        }

        public function getBackendVisualClassname(): string {
            return $this->_backend_visual_classname;
        }

        public function getBackendFormClassname(): string {
            return $this->_backend_form_classname;
        }

        public function getFrontendVisualClassname(): string {
            return $this->_frontend_visual_classname;
        }
    }
}
?>
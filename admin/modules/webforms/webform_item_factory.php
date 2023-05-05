<?php
namespace {
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . 'modules/webforms/visuals/webforms/fields/webform_item_visual.php';
    require_once CMS_ROOT . 'modules/webforms/visuals/webforms/fields/webform_button_visual.php';
    require_once CMS_ROOT . 'modules/webforms/visuals/webforms/fields/webform_textfield_visual.php';
    require_once CMS_ROOT . 'modules/webforms/visuals/webforms/fields/webform_textarea_visual.php';
    require_once CMS_ROOT . 'modules/webforms/form/webform_button_form.php';
    require_once CMS_ROOT . 'modules/webforms/form/webform_textfield_form.php';
    require_once CMS_ROOT . 'modules/webforms/form/webform_textarea_form.php';
    require_once CMS_ROOT . 'core/model/webform_textfield.php';
    require_once CMS_ROOT . 'core/model/webform_textarea.php';
    require_once CMS_ROOT . 'core/model/webform_dropdown.php';
    require_once CMS_ROOT . 'core/model/webform_button.php';
    require_once CMS_ROOT . 'frontend/form_textfield_visual.php';
    require_once CMS_ROOT . 'frontend/form_textarea_visual.php';
    require_once CMS_ROOT . 'frontend/form_dropdown_visual.php';
    require_once CMS_ROOT . 'frontend/form_button_visual.php';

    class WebFormItemFactory {

        private array $_types = array();
        private static ?WebFormItemFactory $_instance = null;

        private function __construct() {
            $this->addType(WebFormTextField::$TYPE, "WebFormTextFieldVisual", "WebFormTextFieldForm", "FormTextFieldVisual");
            $this->addType(WebFormTextArea::$TYPE, "WebFormTextAreaVisual", "WebFormTextAreaForm", "FormTextAreaVisual");
            $this->addType(WebFormDropDown::$TYPE, "WebFormDropDownVisual", "WebFormDropDownForm", "FormDropDownVisual");
            $this->addType(WebFormButton::$TYPE, "WebFormButtonVisual", "WebFormButtonForm", "FormButtonVisual");
        }

        public static function getInstance(): WebFormItemFactory {
            if (!self::$_instance) {
                self::$_instance = new WebFormItemFactory();
            }
            return self::$_instance;
        }

        public function getBackendVisualFor(WebFormItem $webform_item): WebFormItemVisual {
            $backend_visual_classname = $this->getFormItemType($webform_item->getType())->getBackendVisualClassname();
            return new $backend_visual_classname($webform_item);
        }

        public function getBackendFormFor(WebFormItem $webform_item): WebFormItemForm {
            $backend_form_classname = $this->getFormItemType($webform_item->getType())->getBackendFormClassname();
            return new $backend_form_classname($webform_item);
        }

        public function getFrontendVisualFor(WebFormItem $webform_item, Page $page, ?Article $article): FormItemVisual {
            $frontend_form_classname = $this->getFormItemType($webform_item->getType())->getFrontendVisualClassname();
            return new $frontend_form_classname($page, $article, $webform_item);
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
<?php
namespace {
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . 'modules/webforms/visuals/webforms/fields/webform_field_visual.php';
    require_once CMS_ROOT . 'core/model/webform_textfield.php';
    require_once CMS_ROOT . 'core/model/webform_textarea.php';
    require_once CMS_ROOT . 'core/model/webform_dropdown.php';
    require_once CMS_ROOT . 'frontend/form_textfield_visual.php';
    require_once CMS_ROOT . 'frontend/form_textarea_visual.php';
    require_once CMS_ROOT . 'frontend/form_dropdown_visual.php';

    class WebFormFieldFactory {

        private array $_types = array();
        private static ?WebFormFieldFactory $_instance = null;

        private function __construct() {
            $this->addType(WebFormTextField::$TYPE, "WebFormTextFieldVisual", "WebFormTextFieldForm", "FormTextFieldVisual");
            $this->addType(WebFormTextArea::$TYPE, "WebFormTextAreaVisual", "WebFormTextAreaForm", "FormTextAreaVisual");
            $this->addType(WebFormDropDown::$TYPE, "WebFormDropDownVisual", "WebFormDropDownForm", "FormDropDownVisual");
        }

        public static function getInstance(): WebFormFieldFactory {
            if (!self::$_instance) {
                self::$_instance = new WebFormFieldFactory();
            }
            return self::$_instance;
        }

        public function getBackendVisualFor(WebFormField $webform_field): WebFormFieldVisual {
            $backend_visual_classname = $this->getFormFieldType($webform_field->getType())->getBackendVisualClassname();
            return new $backend_visual_classname($webform_field);
        }

        public function getBackendFormFor(WebFormField $webform_field): WebFormFieldForm {
            $backend_form_classname = $this->getFormFieldType($webform_field->getType())->getBackendFormClassname();
            return new $backend_form_classname($webform_field);
        }

        public function getFrontendVisualFor(WebFormField $webform_field, Page $page, ?Article $article): FormFieldVisual {
            $frontend_form_classname = $this->getFormFieldType($webform_field->getType())->getFrontendVisualClassname();
            return new $frontend_form_classname($page, $article, $webform_field);
        }

        private function getFormFieldType(string $type_to_find): WebFormFieldFactory\FormFieldType {
            $found_type = null;
            foreach ($this->_types as $type) {
                if ($type->getTypeName() == $type_to_find) {
                    $found_type = $type;
                }
            }
            return $found_type;
        }

        private function addType(string $type_name, string $backend_visual_classname, string $backend_form_classname, string $frontend_visual_classname): void {
            $this->_types[] = new WebFormFieldFactory\FormFieldType($type_name, $backend_visual_classname, $backend_form_classname, $frontend_visual_classname);
        }
    }
}   
namespace WebFormFieldFactory {     
    class FormFieldType {
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
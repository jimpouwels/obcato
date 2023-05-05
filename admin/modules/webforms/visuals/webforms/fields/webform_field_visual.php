<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/visual.php";
    require_once CMS_ROOT . "core/model/webform_field.php";
    require_once CMS_ROOT . "modules/webforms/visuals/webforms/fields/webform_item_visual.php";

    abstract class WebFormFieldVisual extends WebFormItemVisual {

        public function __construct(WebFormItem $webform_item) {
            parent::__construct($webform_item);
        }

        public function getFormItemTemplate(): string {
            return "modules/webforms/webforms/fields/webform_field.tpl";
        }
        
        abstract function getFormFieldTemplate(): string;

        abstract function loadFieldContent(Smarty_Internal_Data $data): void;

        public function loadItemContent(Smarty_Internal_Data $data): void {
            $form_field_content_template_data = $this->getTemplateEngine()->createChildData();
            $this->loadFieldContent($form_field_content_template_data);
            
            // TODO mandatory checkbox

            $this->assign('field_editor', $this->getTemplateEngine()->fetch($this->getFormFieldTemplate(), $form_field_content_template_data));
        }

    }
?>
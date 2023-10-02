<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/view/views/element_visual.php";
require_once CMS_ROOT . "/view/views/form_textfield.php";
require_once CMS_ROOT . "/view/views/form_pulldown.php";

class FormElementEditorVisual extends ElementVisual {

    private static string $TEMPLATE = "elements/form_element/form_element_form.tpl";
    private FormElement $_form_element;
    private WebFormDao $_webform_dao;

    public function __construct(FormElement $form_element) {
        parent::__construct();
        $this->_form_element = $form_element;
        $this->_webform_dao = WebFormDao::getInstance();
    }

    public function getElement(): Element {
        return $this->_form_element;
    }

    public function renderElementForm(Smarty_Internal_Data $data): string {
        $title_field = new TextField($this->createFieldId("title"), $this->getTextResource("form_element_editor_title"), htmlentities($this->_form_element->getTitle()), false, false, null);
        $current_selected_webform = $this->_form_element->getWebForm();
        $current_webform_id = null;
        if ($current_selected_webform) {
            $current_webform_id = $current_selected_webform->getId();
        }
        $webform_picker = new Pulldown($this->createFieldId("selected_webform"), $this->getTextResource("form_element_editor_webform"), $current_webform_id, array(), false, null, true);
        foreach ($this->_webform_dao->getAllWebForms() as $webform) {
            $webform_picker->addOption($webform->getTitle(), $webform->getId());
        }

        $data->assign("title_field", $title_field->render());
        $data->assign("webform_picker", $webform_picker->render());
        return $this->getTemplateEngine()->fetch(self::$TEMPLATE, $data);
    }

    private function createFieldId($property_name): string {
        return "element_{$this->_form_element->getId()}_{$property_name}";
    }

}

?>

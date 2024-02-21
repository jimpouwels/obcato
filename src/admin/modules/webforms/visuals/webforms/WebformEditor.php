<?php

namespace Obcato\Core\admin\modules\webforms\visuals\webforms;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;
use Obcato\Core\admin\modules\webforms\model\Webform;
use Obcato\Core\admin\modules\webforms\WebformItemFactory;
use Obcato\Core\admin\view\views\Button;
use Obcato\Core\admin\view\views\Panel;

class WebformEditor extends Panel {

    private WebForm $_current_webform;
    private WebformItemFactory $_webform_item_factory;

    public function __construct(TemplateEngine $templateEngine, ?WebForm $current_webform) {
        parent::__construct($templateEngine, "webforms_webform_editor_panel_title");
        $this->_webform_item_factory = WebformItemFactory::getInstance();
        $this->_current_webform = $current_webform;
    }

    public function getPanelContentTemplate(): string {
        return 'modules/webforms/webforms/webform_editor.tpl';
    }

    public function loadPanelContent(TemplateData $data): void {
        $add_textfield_button = new Button($this->getTemplateEngine(), "", "webforms_add_textfield_button_label", "addFormField('textfield');");
        $data->assign("button_add_textfield", $add_textfield_button->render());

        $add_textarea_button = new Button($this->getTemplateEngine(), "", "webforms_add_textarea_button_label", "addFormField('textarea');");
        $data->assign("button_add_textarea", $add_textarea_button->render());

        $add_button_button = new Button($this->getTemplateEngine(), "", "webforms_add_button_button_label", "addFormField('button');");
        $data->assign("button_add_button", $add_button_button->render());

        $data->assign("form_fields", $this->renderFormFields());
    }

    private function renderFormFields(): array {
        $form_fields_data = array();
        foreach ($this->_current_webform->getFormFields() as $form_field) {
            $form_field_data = $this->_webform_item_factory->getBackendVisualFor($form_field);
            $form_fields_data[] = $form_field_data->render();
        }
        return $form_fields_data;
    }

}

?>
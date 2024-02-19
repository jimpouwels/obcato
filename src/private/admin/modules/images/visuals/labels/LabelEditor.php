<?php

class LabelEditor extends Panel {

    private ImageLabel $_current_label;

    public function __construct(TemplateEngine $templateEngine, ImageLabel $current_label) {
        parent::__construct($templateEngine, 'Label bewerken');
        $this->_current_label = $current_label;
    }

    public function getPanelContentTemplate(): string {
        return "modules/images/labels/editor.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $data->assign("id", $this->_current_label->getId());
        $data->assign("label_name_field", $this->renderLabelNameField());
    }

    private function renderLabelNameField(): string {
        $name_field = new TextField(TemplateEngine::getInstance(), "name", "Naam", $this->_current_label->getName(), true, false, null);
        return $name_field->render();
    }
}

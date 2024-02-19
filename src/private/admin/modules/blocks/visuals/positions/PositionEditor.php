<?php

class PositionEditor extends Panel {

    private BlockPosition $currentPosition;

    public function __construct(TemplateEngine $templateEngine, BlockPosition $currentPosition) {
        parent::__construct($templateEngine, $this->getTextResource('blocks_edit_position_title'));
        $this->currentPosition = $currentPosition;
    }

    public function getPanelContentTemplate(): string {
        return "modules/blocks/positions/editor.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $positionId = $this->currentPosition->getId();
        $data->assign("id", $positionId);
        $data->assign("name_field", $this->renderNameField());
        $data->assign("explanation_field", $this->renderExplanationField());
    }

    private function renderNameField(): string {
        $nameField = new TextField($this->getTemplateEngine(), "name", $this->getTextResource("blocks_position_name_field"), $this->currentPosition->getName(), true, false, null);
        return $nameField->render();
    }

    private function renderExplanationField(): string {
        $explanationField = new TextField($this->getTemplateEngine(), "explanation", $this->getTextResource("blocks_position_explanation_field"), $this->currentPosition->getExplanation(), false, false, null);
        return $explanationField->render();
    }
}

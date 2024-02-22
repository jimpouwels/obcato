<?php

namespace Obcato\Core\admin\modules\blocks\visuals\positions;

use Obcato\Core\admin\modules\blocks\model\BlockPosition;
use Obcato\Core\admin\view\TemplateData;
use Obcato\Core\admin\view\views\Panel;
use Obcato\Core\admin\view\views\TextField;

class PositionEditor extends Panel {

    private BlockPosition $currentPosition;

    public function __construct(BlockPosition $currentPosition) {
        parent::__construct($this->getTextResource('blocks_edit_position_title'));
        $this->currentPosition = $currentPosition;
    }

    public function getPanelContentTemplate(): string {
        return "modules/blocks/positions/editor.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $positionId = $this->currentPosition->getId();
        $data->assign("id", $positionId);
        $data->assign("name_field", $this->renderNameField());
        $data->assign("explanation_field", $this->renderExplanationField());
    }

    private function renderNameField(): string {
        $nameField = new TextField("name", $this->getTextResource("blocks_position_name_field"), $this->currentPosition->getName(), true, false, null);
        return $nameField->render();
    }

    private function renderExplanationField(): string {
        $explanationField = new TextField("explanation", $this->getTextResource("blocks_position_explanation_field"), $this->currentPosition->getExplanation(), false, false, null);
        return $explanationField->render();
    }
}

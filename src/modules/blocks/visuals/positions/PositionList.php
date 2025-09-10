<?php

namespace Obcato\Core\modules\blocks\visuals\positions;

use Obcato\Core\database\dao\BlockDao;
use Obcato\Core\database\dao\BlockDaoMysql;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\InformationMessage;
use Obcato\Core\view\views\Panel;
use Obcato\Core\view\views\SingleCheckbox;

class PositionList extends Panel {

    private BlockDao $blockDao;

    public function __construct() {
        parent::__construct('Posities');
        $this->blockDao = BlockDaoMysql::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "blocks/templates/positions/list.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign("all_positions", $this->getAllPositions());
        $noPositionsMessage = new InformationMessage($this->getTextResource("blocks_no_positions_found"));
        $data->assign("no_positions_message", $noPositionsMessage->render());
    }

    private function getAllPositions(): array {
        $allPositionsValues = array();
        $allPositions = $this->blockDao->getBlockPositions();

        foreach ($allPositions as $position) {
            $positionValue = array();
            $positionValue["id"] = $position->getId();
            $positionValue["name"] = $position->getName();
            $positionValue["explanation"] = $position->getExplanation();
            $deleteField = new SingleCheckbox("position_" . $position->getId() . "_delete", "", false, false, "");
            $positionValue["delete_field"] = $deleteField->render();

            $allPositionsValues[] = $positionValue;
        }
        return $allPositionsValues;
    }
}

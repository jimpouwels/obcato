<?php
require_once CMS_ROOT . "/view/views/InformationMessage.php";

class PositionList extends Panel {

    private BlockDao $blockDao;

    public function __construct(TemplateEngine $templateEngine) {
        parent::__construct($templateEngine, 'Posities');
        $this->blockDao = BlockDaoMysql::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "modules/blocks/positions/list.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign("all_positions", $this->getAllPositions());
        $noPositionsMessage = new InformationMessage($this->getTemplateEngine(), $this->getTextResource("blocks_no_positions_found"));
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
            $deleteField = new SingleCheckbox($this->getTemplateEngine(), "position_" . $position->getId() . "_delete", "", false, false, "");
            $positionValue["delete_field"] = $deleteField->render();

            $allPositionsValues[] = $positionValue;
        }
        return $allPositionsValues;
    }
}

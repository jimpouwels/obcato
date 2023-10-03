<?php

defined('_ACCESS') or die;

require_once CMS_ROOT . "/request_handlers/HttpRequestHandler.php";
require_once CMS_ROOT . "/database/dao/BlockDaoMysql.php";
require_once CMS_ROOT . "/modules/blocks/PositionForm.php";

class PositionRequestHandler extends HttpRequestHandler {

    private static string $POSITION_ID_POST = "position_id";
    private static string $POSITION_ID_GET = "position";
    private BlockDao $_block_dao;
    private ?BlockPosition $_current_position;

    public function __construct() {
        $this->_block_dao = BlockDaoMysql::getInstance();
    }

    public function handleGet(): void {
        $this->_current_position = $this->getPositionFromGetRequest();
    }

    public function handlePost(): void {
        $this->_current_position = $this->getPositionFromPostRequest();
        if ($this->isAddPositionAction()) {
            $this->addPosition();
        } else if ($this->isUpdatePositionAction()) {
            $this->updatePosition();
        } else if ($this->isDeletePositionsAction()) {
            $this->deletePositions();
        }
    }

    public function getCurrentPosition(): ?BlockPosition {
        return $this->_current_position;
    }

    private function addPosition(): void {
        $new_position = $this->_block_dao->createBlockPosition();
        $new_position->setName($this->getTextResource("blocks_default_position_title"));
        $this->_block_dao->updateBlockPosition($new_position);
        $this->sendSuccessMessage($this->getTextResource("blocks_position_successfully_created"));
        $this->redirectTo($this->getBackendBaseUrl() . "&position=" . $new_position->getId());
    }

    private function updatePosition(): void {
        $position_form = new PositionForm($this->_current_position);
        try {
            $position_form->loadFields();
            $this->_block_dao->updateBlockPosition($this->_current_position);
            $this->sendSuccessMessage($this->getTextResource("blocks_position_successfully_updated"));
        } catch (FormException $e) {
            $this->sendErrorMessage($this->getTextResource("blocks_position_could_not_be_updated_error"));
        }
    }

    private function deletePositions(): void {
        $positions = $this->_block_dao->getBlockPositions();
        foreach ($positions as $position) {
            if (isset($_POST["position_" . $position->getId() . "_delete"]))
                $this->_block_dao->deleteBlockPosition($position);
        }
        $this->sendSuccessMessage($this->getTextResource("blocks_position_successfully_deleted"));
    }

    private function getPositionFromGetRequest(): ?BlockPosition {
        if (isset($_GET[self::$POSITION_ID_GET])) {
            return $this->getPositionFromDatabase($_GET[self::$POSITION_ID_GET]);
        }
        return null;
    }

    private function getPositionFromPostRequest(): ?BlockPosition {
        if (isset($_POST[self::$POSITION_ID_POST])) {
            return $this->getPositionFromDatabase($_POST[self::$POSITION_ID_POST]);
        }
        return null;
    }

    private function getPositionFromDatabase($position_id): BlockPosition {
        return $this->_block_dao->getBlockPosition($position_id);
    }

    private function isAddPositionAction(): bool {
        return isset($_POST["add_position_action"]);
    }

    private function isUpdatePositionAction(): bool {
        return isset($_POST["action"]) && $_POST["action"] == "update_position";
    }

    private function isDeletePositionsAction(): bool {
        return isset($_POST["position_delete_action"]);
    }
}

?>
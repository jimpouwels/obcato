<?php

namespace Obcato\Core\admin\modules\blocks;

use Obcato\Core\admin\core\form\FormException;
use Obcato\Core\admin\database\dao\BlockDao;
use Obcato\Core\admin\database\dao\BlockDaoMysql;
use Obcato\Core\admin\modules\blocks\model\BlockPosition;
use Obcato\Core\admin\request_handlers\HttpRequestHandler;

class PositionRequestHandler extends HttpRequestHandler {

    private static string $POSITION_ID_POST = "position_id";
    private static string $POSITION_ID_GET = "position";
    private BlockDao $blockDao;
    private ?BlockPosition $currentPosition;

    public function __construct() {
        $this->blockDao = BlockDaoMysql::getInstance();
    }

    public function handleGet(): void {
        $this->currentPosition = $this->getPositionFromGetRequest();
    }

    public function handlePost(): void {
        $this->currentPosition = $this->getPositionFromPostRequest();
        if ($this->isAddPositionAction()) {
            $this->addPosition();
        } else if ($this->isUpdatePositionAction()) {
            $this->updatePosition();
        } else if ($this->isDeletePositionsAction()) {
            $this->deletePositions();
        }
    }

    public function getCurrentPosition(): ?BlockPosition {
        return $this->currentPosition;
    }

    private function addPosition(): void {
        $newPosition = $this->blockDao->createBlockPosition();
        $newPosition->setName($this->getTextResource("blocks_default_position_title"));
        $this->blockDao->updateBlockPosition($newPosition);
        $this->sendSuccessMessage($this->getTextResource("blocks_position_successfully_created"));
        $this->redirectTo($this->getBackendBaseUrl() . "&position=" . $newPosition->getId());
    }

    private function updatePosition(): void {
        $positionForm = new PositionForm($this->currentPosition);
        try {
            $positionForm->loadFields();
            $this->blockDao->updateBlockPosition($this->currentPosition);
            $this->sendSuccessMessage($this->getTextResource("blocks_position_successfully_updated"));
        } catch (FormException $e) {
            $this->sendErrorMessage($this->getTextResource("blocks_position_could_not_be_updated_error"));
        }
    }

    private function deletePositions(): void {
        $positions = $this->blockDao->getBlockPositions();
        foreach ($positions as $position) {
            if (isset($_POST["position_" . $position->getId() . "_delete"]))
                $this->blockDao->deleteBlockPosition($position);
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
        return $this->blockDao->getBlockPosition($position_id);
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
<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/request_handlers/element_holder_request_handler.php";
require_once CMS_ROOT . "/database/dao/block_dao.php";
require_once CMS_ROOT . "/database/dao/element_dao.php";
require_once CMS_ROOT . "/modules/blocks/block_form.php";

class BlockRequestHandler extends ElementHolderRequestHandler {

    private static string $BLOCK_ID_POST = "element_holder_id";
    private static string $BLOCK_ID_GET = "block";
    private BlockDao $_block_dao;
    private ?Block $_current_block;

    public function __construct() {
        parent::__construct();
        $this->_block_dao = BlockDao::getInstance();
    }

    public function handleGet(): void {
        $this->_current_block = $this->getBlockFromGetRequest();
    }

    public function handlePost(): void {
        parent::handlePost();
        $this->_current_block = $this->getBlockFromPostRequest();
        if ($this->isUpdateBlockAction()) {
            $this->updateBlock();
        } else if ($this->isDeleteBlockAction()) {
            $this->deleteBlock();
        } else if ($this->isAddBlockAction()) {
            $this->addBlock();
        }
    }

    public function getCurrentBlock(): ?Block {
        return $this->_current_block;
    }

    private function updateBlock(): void {
        $block_form = new BlockForm($this->_current_block);
        try {
            $block_form->loadFields();
            $this->_block_dao->updateBlock($this->_current_block);
            $this->updateElementHolder($this->_current_block);
            $this->sendSuccessMessage("Blok succesvol opgeslagen");
        } catch (FormException $e) {
            $this->sendErrorMessage("Blok niet opgeslagen, verwerk de fouten");
        } catch (ElementHolderContainsErrorsException $e) {
            $this->sendErrorMessage("Artikel niet opgeslagen, verwerk de fouten");
        }
    }

    private function deleteBlock(): void {
        $this->_block_dao->deleteBlock($this->_current_block);
        $this->sendSuccessMessage("Blok succesvol verwijderd");
        $this->redirectTo($this->getBackendBaseUrl());
    }

    private function addBlock(): void {
        $new_block = $this->_block_dao->createBlock();
        $this->sendSuccessMessage("Blok succesvol aangemaakt");
        $this->redirectTo($this->getBackendBaseUrl() . "&block=" . $new_block->getId());
    }

    private function getBlockFromGetRequest(): ?Block {
        if (isset($_GET[self::$BLOCK_ID_GET])) {
            return $this->getBlockFromDatabase($_GET[self::$BLOCK_ID_GET]);
        }
        return null;
    }

    private function getBlockFromPostRequest(): ?Block {
        if (isset($_POST[self::$BLOCK_ID_POST])) {
            return $this->getBlockFromDatabase($_POST[self::$BLOCK_ID_POST]);
        }
        return null;
    }

    private function getBlockFromDatabase($block_id): Block {
        return $this->_block_dao->getBlock($block_id);
    }

    private function isUpdateBlockAction(): bool {
        return isset($_POST["action"]) && $_POST["action"] == "update_element_holder";
    }

    private function isDeleteBlockAction(): bool {
        return isset($_POST["action"]) && $_POST["action"] == "delete_block";
    }

    private function isAddBlockAction(): bool {
        return isset($_POST['add_block_action']);
    }
}
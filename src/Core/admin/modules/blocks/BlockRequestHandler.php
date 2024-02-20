<?php

namespace Obcato\Core;

class BlockRequestHandler extends ElementHolderRequestHandler {

    private static string $BLOCK_ID_POST = "element_holder_id";
    private static string $BLOCK_ID_GET = "block";
    private BlockDao $blockDao;
    private ?Block $currentBlock;

    public function __construct() {
        parent::__construct();
        $this->blockDao = BlockDaoMysql::getInstance();
    }

    public function handleGet(): void {
        $this->currentBlock = $this->getBlockFromGetRequest();
    }

    public function handlePost(): void {
        try {
            parent::handlePost();
            if ($this->isUpdateBlockAction()) {
                $this->updateBlock();
            } else if ($this->isDeleteBlockAction()) {
                $this->deleteBlock();
            } else if ($this->isAddBlockAction()) {
                $this->addBlock();
            }
        } catch (ElementHolderContainsErrorsException) {
            $this->sendErrorMessage($this->getTextResource("blocks_notification_not_updated_error"));
        }
    }

    public function loadElementHolderFromPostRequest(): ?ElementHolder {
        $this->currentBlock = $this->getBlockFromPostRequest();
        return $this->currentBlock;
    }

    public function getCurrentBlock(): ?Block {
        return $this->currentBlock;
    }

    private function updateBlock(): void {
        try {
            $blockForm = new BlockForm($this->currentBlock);
            $blockForm->loadFields();
            $this->blockDao->updateBlock($this->currentBlock);
            $this->updateElementHolder($this->currentBlock);
            $this->sendSuccessMessage($this->getTextResource("blocks_notification_successfully_updated"));
        } catch (FormException|ElementHolderContainsErrorsException) {
            $this->sendErrorMessage($this->getTextResource("blocks_notification_not_updated_error"));
        }
    }

    private function deleteBlock(): void {
        $this->blockDao->deleteBlock($this->currentBlock);
        $this->sendSuccessMessage($this->getTextResource("blocks_notification_successfully_deleted"));
        $this->redirectTo($this->getBackendBaseUrl());
    }

    private function addBlock(): void {
        $newBlock = $this->blockDao->createBlock();
        $this->sendSuccessMessage($this->getTextResource("blocks_notification_successfully_created"));
        $this->redirectTo($this->getBackendBaseUrl() . "&block=" . $newBlock->getId());
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
        return $this->blockDao->getBlock($block_id);
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
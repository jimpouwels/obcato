<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "request_handlers/element_holder_request_handler.php";
    require_once CMS_ROOT . "database/dao/block_dao.php";
    require_once CMS_ROOT . "database/dao/element_dao.php";
    require_once CMS_ROOT . "modules/blocks/block_form.php";

    class BlockPreHandler extends ElementHolderRequestHandler {

        private static $BLOCK_ID_POST = "element_holder_id";
        private static $BLOCK_ID_GET = "block";
        private $_block_dao;
        private $_element_dao;
        private $_current_block;

        public function __construct() {
            parent::__construct();
            $this->_block_dao = BlockDao::getInstance();
            $this->_element_dao = ElementDao::getInstance();
        }

        public function handleGet() {
            $this->_current_block = $this->getBlockFromGetRequest();
        }

        public function handlePost() {
            parent::handlePost();
            $this->_current_block = $this->getBlockFromPostRequest();
            if ($this->isUpdateBlockAction())
                $this->updateBlock();
            else if ($this->isDeleteBlockAction())
                $this->deleteBlock();
            else if ($this->isAddBlockAction())
                $this->addBlock();
        }

        public function getCurrentBlock() {
            return $this->_current_block;
        }

        private function updateBlock() {
            $block_form = new BlockForm($this->_current_block);
            try {
                $block_form->loadFields();
                $this->_element_dao->updateElementOrder($block_form->getElementOrder(), $this->_current_block);
                $this->_block_dao->updateBlock($this->_current_block);
                $this->updateElementHolder($this->_current_block);
                $this->sendSuccessMessage("Blok succesvol opgeslagen");
            } catch (FormException $e) {
                $this->sendErrorMessage("Blok niet opgeslagen, verwerk de fouten");
            }
        }

        private function deleteBlock() {
            $this->_block_dao->deleteBlock($this->_current_block);
            $this->sendSuccessMessage("Blok succesvol verwijderd");
            $this->redirectTo("/admin/index.php");
        }

        private function addBlock() {
            $new_block = $this->_block_dao->createBlock();
            $this->sendSuccessMessage("Blok succesvol aangemaakt");
            $this->redirectTo("/admin/index.php?block=" . $new_block->getId());
        }

        private function getBlockFromGetRequest() {
            if (isset($_GET[self::$BLOCK_ID_GET]))
                return $this->getBlockFromDatabase($_GET[self::$BLOCK_ID_GET]);
        }

        private function getBlockFromPostRequest() {
            if (isset($_POST[self::$BLOCK_ID_POST]))
                return $this->getBlockFromDatabase($_POST[self::$BLOCK_ID_POST]);
        }

        private function getBlockFromDatabase($block_id) {
            return $this->_block_dao->getBlock($block_id);
        }

        private function isUpdateBlockAction() {
            return isset($_POST["action"]) && $_POST["action"] == "update_element_holder";
        }

        private function isDeleteBlockAction() {
            return isset($_POST["action"]) && $_POST["action"] == "delete_block";
        }

        private function isAddBlockAction() {
            return isset($_POST['add_block_action']);
        }
    }
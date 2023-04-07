<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "request_handlers/http_request_handler.php";
    require_once CMS_ROOT . "database/dao/block_dao.php";
    require_once CMS_ROOT . "modules/blocks/position_form.php";
    
    class PositionPreHandler extends HttpRequestHandler {

        private static $POSITION_ID_POST = "position_id";
        private static $POSITION_ID_GET = "position";
        private $_block_dao;
        private $_current_position;
        
        public function __construct() {
            $this->_block_dao = BlockDao::getInstance();
        }
        
        public function handleGet() {
            $this->_current_position = $this->getPositionFromGetRequest();
        }
        
        public function handlePost() {
            $this->_current_position = $this->getPositionFromPostRequest();
            if ($this->isAddPositionAction())
                $this->addPosition();
            else if ($this->isUpdatePositionAction())
                $this->updatePosition();
            else if ($this->isDeletePositionsAction())
                $this->deletePositions();
        }
        
        public function getCurrentPosition() {
            return $this->_current_position;
        }
        
        private function addPosition() {
            $new_position = $this->_block_dao->createBlockPosition();
            $new_position->setName($this->getTextResource("blocks_default_position_title"));
            $this->_block_dao->updateBlockPosition($new_position);
            $this->sendSuccessMessage($this->getTextResource("blocks_position_successfully_created"));
            $this->redirectTo($this->getBackendBaseUrl() . "&position=" . $new_position->getId());
        }
        
        private function updatePosition() {
            $position_form = new PositionForm($this->_current_position);
            try {
                $position_form->loadFields();
                $this->_block_dao->updateBlockPosition($this->_current_position);
                $this->sendSuccessMessage($this->getTextResource("blocks_position_successfully_updated"));
            } catch (FormException $e) {
                $this->sendErrorMessage($this->getTextResource("blocks_position_could_not_be_updated_error"));
            }
        }
        
        private function deletePositions() {
            $positions = $this->_block_dao->getBlockPositions();
            foreach ($positions as $position) {
                if (isset($_POST["position_" . $position->getId() . "_delete"]))
                    $this->_block_dao->deleteBlockPosition($position);
            }
            $this->sendSuccessMessage($this->getTextResource("blocks_position_successfully_deleted"));
        }
        
        private function getPositionFromGetRequest() {
            if (isset($_GET[self::$POSITION_ID_GET]))
                return $this->getPositionFromDatabase($_GET[self::$POSITION_ID_GET]);
        }
        
        private function getPositionFromPostRequest() {
            if (isset($_POST[self::$POSITION_ID_POST]))
                return $this->getPositionFromDatabase($_POST[self::$POSITION_ID_POST]);
        }
        
        private function getPositionFromDatabase($position_id) {
                return $this->_block_dao->getBlockPosition($position_id);
        }
        
        private function isAddPositionAction() {
            return isset($_POST["add_position_action"]);
        }
        
        private function isUpdatePositionAction() {
            return isset($_POST["action"]) && $_POST["action"] == "update_position";
        }
        
        private function isDeletePositionsAction() {
            return isset($_POST["position_delete_action"]);
        }
    }
    
?>
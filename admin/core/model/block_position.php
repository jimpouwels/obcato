<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "core/model/entity.php";
    require_once CMS_ROOT . "database/dao/block_dao.php";

    class BlockPosition extends Entity {
    
        private string $_name = "";
        private string $_explanation = "";
        private BlockDao $_block_dao;
        
        public function __construct() {
            $this->_block_dao = BlockDao::getInstance();
        }
        
        public function getName(): string {
            return $this->_name;
        }
        
        public function setName($name): void {
            $this->_name = $name;
        }
        
        public function getExplanation(): string {
            return $this->_explanation;
        }
        
        public function setExplanation(string $explanation): void {
            $this->_explanation = $explanation;
        }
        
        public function getBlocks(): array {
            return $this->_block_dao->getBlocksByPosition($this);
        }
        
        public static function constructFromRecord($record): BlockPosition {
            $position = new BlockPosition();
            $position->setId($record['id']);
            $position->setName($record['name']);
            $position->setExplanation($record['explanation']);
            
            return $position;
        }
    
    }
    
?>
<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/element_holder.php";

    class Block extends ElementHolder {
    
        private int $_position_id;
        
        public function getPositionId(): int {
            return $this->_position_id;
        }
        
        public function setPositionId(int $position_id): void {
            $this->_position_id = $position_id;
        }
        
        public function getPosition(): BlockPosition {
            $dao = BlockDao::getInstance();
            return $dao->getBlockPosition($this->_position_id);
        }
        
        public function getPositionName(): string {
            $position_name = "";
            $position = $this->getPosition();
            if (!is_null($position)) {
                $position_name = $position->getName();
            }
            return $position_name;
        }
        
        public static function constructFromRecord(array $record): Block {
            $block = new Block();
            $block->setId($record['id']);
            $block->setTitle($record['title']);
            $block->setPublished($record['published'] == 1 ? true : false);
            $block->setTemplateId($record['template_id']);
            $block->setScopeId($record['scope_id']);
            $block->setPositionId($record['position_id']);
            $block->setCreatedAt($record['created_at']);
            $block->setCreatedById($record['created_by']);
            $block->setType($record['type']);
            return $block;
        }
    
    }
    
?>
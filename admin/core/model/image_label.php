<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/entity.php";

    class ImageLabel extends Entity {
    
        private string $_name;
        
        public function setName(string $name): void {
            $this->_name = $name;
        }
        
        public function getName(): string {
            return $this->_name;
        }
        
        public static function constructFromRecord(array $record): ImageLabel {
            $label = new ImageLabel();
            $label->setId($record['id']);
            $label->setName($record['name']);
            
            return $label;
        }
    
    }
    
?>
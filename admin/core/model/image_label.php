<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/entity.php";

    class ImageLabel extends Entity {
    
        private $_name;
        
        public function setName($name) {
            $this->_name = $name;
        }
        
        public function getName() {
            return $this->_name;
        }
        
        public static function constructFromRecord($record) {
            $label = new ImageLabel();
            $label->setId($record['id']);
            $label->setName($record['name']);
            
            return $label;
        }
    
    }
    
?>
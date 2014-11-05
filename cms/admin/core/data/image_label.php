<?php

    
    defined('_ACCESS') or die;
    
    include_once CMS_ROOT . "core/data/entity.php";

    class ImageLabel extends Entity {
    
        private $_name;
        
        public function setName($name) {
            $this->_name = $name;
        }
        
        public function getName() {
            return $this->_name;
        }
        
        public function persist() {
        }
        
        public function update() {
        }
        
        public function delete() {
        }
        
        public static function constructFromRecord($record) {
            $label = new ImageLabel();
            $label->setId($record['id']);
            $label->setName($record['name']);
            
            return $label;
        }
    
    }
    
?>
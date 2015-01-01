<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/entity.php";

    class Scope extends Entity {
    
        private $_name;
        
        public function getName() {
            return $this->_name;
        }
        
        public function setName($name) {
            $this->_name = $name;
        }
        
        public static function constructFromRecord($record) {
            $scope = new Scope();
            $scope->setId($record['id']);
            $scope->setName($record['name']);
            
            return $scope;
        }
    
    }
    
?>
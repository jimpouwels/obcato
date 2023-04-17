<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/entity.php";

    class Scope extends Entity {
    
        private string $_name;
        
        public function getName(): string {
            return $this->_name;
        }
        
        public function setName(string $name): void {
            $this->_name = $name;
        }
        
        public static function constructFromRecord(array $record): Scope {
            $scope = new Scope();
            $scope->setId($record['id']);
            $scope->setName($record['name']);
            
            return $scope;
        }
    
    }
    
?>
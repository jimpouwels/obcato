<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/entity.php";

    class Scope extends Entity {
    
        private string $_identifier;
        
        public function getIdentifier(): string {
            return $this->_identifier;
        }
        
        public function setIdentifier(string $identifier): void {
            $this->_identifier = $identifier;
        }
        
        public static function constructFromRecord(array $record): Scope {
            $scope = new Scope();
            $scope->setId($record['id']);
            $scope->setIdentifier($record['identifier']);
            
            return $scope;
        }
    
    }
    
?>
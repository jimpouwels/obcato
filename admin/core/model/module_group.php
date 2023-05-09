<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/entity.php";
    
    class ModuleGroup extends Entity {
    
        private string $_identifier;
        private int $_element_group;
        
        public function getIdentifier(): string {
            return $this->_identifier;
        }
        
        public function setIdentifier(string $identifier): void {
            $this->_identifier = $identifier;
        }
        
        public function isElementGroup(): bool {
            return $this->_element_group;
        }
        
        public function setElementGroup(int $element_group): void {
            $this->_element_group = $element_group;
        }
                
        public function getModules(): array {
            $mysql_database = MysqlConnector::getInstance(); 
            
            $query = "SELECT * FROM modules WHERE module_group_id = " . $this->getId();
            $result = $mysql_database->executeQuery($query);
            $modules = array();
            while ($row = $result->fetch_assoc()) {
                $module = Module::constructFromRecord($row);
                if (!is_null($module)) {
                    array_push($modules, $module);
                }
            }
            
            return $modules;
        }
        
        public static function constructFromRecord(array $row): ModuleGroup {
            $module_group = new ModuleGroup();
            $module_group->initFromDb($row);
            return $module_group;
        }
        
        protected function initFromDb(array $row): void {
            $this->setIdentifier($row['identifier']);
            $this->setElementGroup($row['element_group']);
            parent::initFromDb($row);
        }
    
    }
    
?>
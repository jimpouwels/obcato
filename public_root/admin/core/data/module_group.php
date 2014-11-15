<?php

    
    defined('_ACCESS') or die;

    include_once CMS_ROOT . "core/data/entity.php";
    
    class ModuleGroup extends Entity {
    
        private $_title;
        private $_element_group;
        
        public function getTitle() {
            return $this->_title;
        }
        
        public function setTitle($title) {
            $this->_title = $title;
        }
        
        public function isElementGroup() {
            return $this->_element_group;
        }
        
        public function setElementGroup($element_group) {
            $this->_element_group = $element_group;
        }
                
        public function getModules() {
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
        
        public static function constructFromRecord($record) {
            $module_group = new ModuleGroup();
            $module_group->setId($record['id']);
            $module_group->setTitle($record['title']);
            $module_group->setElementGroup($record['element_group']);
            
            return $module_group;
        }
    
    }
    
?>
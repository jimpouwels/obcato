<?php

    
    defined('_ACCESS') or die;

    include_once CMS_ROOT . "database/mysql_connector.php";
    include_once CMS_ROOT . "core/data/module_group.php";
    include_once CMS_ROOT . "core/data/module.php";

    class ModuleDao {

        private static $instance;
        private $_mysql_connector;

        private function __construct() {
            $this->_mysql_connector = MysqlConnector::getInstance();
        }
        
        public static function getInstance() {
            if (!self::$instance) {
                self::$instance = new ModuleDao();
            }
            return self::$instance;
        }

        public function getAllModules() {
            $query = "SELECT * FROM modules ORDER BY title";
            $result = $this->_mysql_connector->executeQuery($query);
            $modules = array();
            while ($row = $result->fetch_assoc()) {
                $module = Module::constructFromRecord($row);
                array_push($modules, $module);
            }
            return $modules;
        }

        public function getModule($id) {
            $query = "SELECT * FROM modules WHERE id = " . $id;
            $result = $this->_mysql_connector->executeQuery($query);
            
            $module = NULL;
            while ($row = $result->fetch_assoc()) {
                $module = Module::constructFromRecord($row);
                
                break;
            }
            return $module;
        }

        public function removeModule($identifier) {
            $statement = $this->_mysql_connector->prepareStatement('DELETE FROM modules WHERE identifier = ?');
            $statement->bind_param('s', $identifier);
            $this->_mysql_connector->executeStatement($statement);
        }

        public function persistModule($module) {
            $query = 'INSERT INTO modules (title, icon_url, module_group_id, popup, identifier, enabled, system_default, class)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
            $statement = $this->_mysql_connector->prepareStatement($query);
            $title = $module->getTitle();
            $icon_url = $module->getIconUrl();
            $module_group_id = $module->getModuleGroupId();
            $popup = $module->isPopup() ? 1 : 0;
            $identifier = $module->getIdentifier();
            $enabled = $module->isEnabled() ? 1 : 0;
            $system_default = $module->isSystemDefault() ? 1 : 0;
            $class = $module->getClass();
            $statement->bind_param('ssiisiis', $title, $icon_url, $module_group_id, $popup, $identifier, $enabled, $system_default, $class);
            $this->_mysql_connector->executeStatement($statement);
        }

        public function updateModule($module) {
            $query = 'UPDATE modules set title = ?, icon_url = ?, module_group_id = ?, popup = ?, class = ? WHERE identifier = ?';
            $statement = $this->_mysql_connector->prepareStatement($query);
            $title = $module->getTitle();
            $icon_url = $module->getIconUrl();
            $module_group_id = $module->getModuleGroupId();
            $popup = $module->isPopup() ? 1 : 0;
            $class = $module->getClass();
            $identifier = $module->getIdentifier();
            $statement->bind_param('ssiiss', $title, $icon_url, $module_group_id, $popup, $class, $identifier);
            $this->_mysql_connector->executeStatement($statement);
        }

        public function getModuleByIdentifier($identifier) {
            $query = "SELECT * FROM modules WHERE identifier = '" . $identifier . "'";
            $result = $this->_mysql_connector->executeQuery($query);
            
            $module = NULL;
            while ($row = $result->fetch_assoc()) {
                $module = Module::constructFromRecord($row);
                break;
            }
            return $module;
        }

        public function getModuleGroups() {
            $query = "SELECT * FROM module_groups ORDER BY follow_up";
            $result = $this->_mysql_connector->executeQuery($query);
            $groups = array();
            
            while ($row = $result->fetch_assoc()) {
                $module_group = ModuleGroup::constructFromRecord($row);
                array_push($groups, $module_group);
            }
            return $groups;
        }

        public function getModuleGroupByTitle($title) {
            $statement = $this->_mysql_connector->prepareStatement('SELECT * FROM module_groups WHERE title = ?');
            $statement->bind_param('s', $title);
            $result = $this->_mysql_connector->executeStatement($statement);
            while ($row = $result->fetch_assoc()) {
                return ModuleGroup::constructFromRecord($row);
            }
        }
        
    }
?>
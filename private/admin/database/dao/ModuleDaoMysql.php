<?php
require_once CMS_ROOT . "/database/MysqlConnector.php";
require_once CMS_ROOT . "/core/model/ModuleGroup.php";
require_once CMS_ROOT . "/core/model/Module.php";
require_once CMS_ROOT . "/database/dao/ModuleDao.php";

class ModuleDaoMysql implements ModuleDao {

    private static ?ModuleDaoMysql $instance = null;
    private MysqlConnector $mysqlConnector;

    private function __construct() {
        $this->mysqlConnector = MysqlConnector::getInstance();
    }

    public static function getInstance(): ModuleDaoMysql {
        if (!self::$instance) {
            self::$instance = new ModuleDaoMysql();
        }
        return self::$instance;
    }

    public function getAllModules(): array {
        $query = "SELECT * FROM modules ORDER BY identifier";
        $result = $this->mysqlConnector->executeQuery($query);
        $modules = array();
        while ($row = $result->fetch_assoc()) {
            $module = Module::constructFromRecord($row);
            $modules[] = $module;
        }
        return $modules;
    }

    public function getModule(int $id): ?Module {
        $query = "SELECT * FROM modules WHERE id = " . $id;
        $result = $this->mysqlConnector->executeQuery($query);
        while ($row = $result->fetch_assoc()) {
            return Module::constructFromRecord($row);
        }
        return null;
    }

    public function removeModule(string $identifier): void {
        $statement = $this->mysqlConnector->prepareStatement('DELETE FROM modules WHERE identifier = ?');
        $statement->bind_param('s', $identifier);
        $this->mysqlConnector->executeStatement($statement);
    }

    public function persistModule(Module $module): void {
        $query = 'INSERT INTO modules (module_group_id, popup, identifier, enabled, system_default, class)
                      VALUES (?, ?, ?, ?, ?, ?)';
        $statement = $this->mysqlConnector->prepareStatement($query);
        $moduleGroupId = $module->getModuleGroupId();
        $popup = $module->isPopup() ? 1 : 0;
        $identifier = $module->getIdentifier();
        $enabled = $module->isEnabled() ? 1 : 0;
        $systemDefault = $module->isSystemDefault() ? 1 : 0;
        $class = $module->getClass();
        $statement->bind_param('siiiis', $moduleGroupId, $popup, $identifier, $enabled, $systemDefault, $class);
        $this->mysqlConnector->executeStatement($statement);
    }

    public function updateModule(Module $module): void {
        $query = 'UPDATE modules set module_group_id = ?, popup = ?, class = ? WHERE identifier = ?';
        $statement = $this->mysqlConnector->prepareStatement($query);
        $moduleGroupId = $module->getModuleGroupId();
        $popup = $module->isPopup() ? 1 : 0;
        $class = $module->getClass();
        $identifier = $module->getIdentifier();
        $statement->bind_param('iiss', $moduleGroupId, $popup, $class, $identifier);
        $this->mysqlConnector->executeStatement($statement);
    }

    public function getModuleByIdentifier(string $identifier): ?Module {
        $query = "SELECT * FROM modules WHERE identifier = '" . $identifier . "'";
        $result = $this->mysqlConnector->executeQuery($query);
        while ($row = $result->fetch_assoc()) {
            return Module::constructFromRecord($row);
        }
        return null;
    }

    public function getModuleGroups(): array {
        $query = "SELECT * FROM module_groups ORDER BY follow_up";
        $result = $this->mysqlConnector->executeQuery($query);
        $groups = array();
        while ($row = $result->fetch_assoc()) {
            $groups[] = ModuleGroup::constructFromRecord($row);
        }
        return $groups;
    }

    public function getModuleGroupByIdentifier(string $identifier): ?ModuleGroup {
        $statement = $this->mysqlConnector->prepareStatement('SELECT * FROM module_groups WHERE identifier = ?');
        $statement->bind_param('s', $identifier);
        $result = $this->mysqlConnector->executeStatement($statement);
        while ($row = $result->fetch_assoc()) {
            return ModuleGroup::constructFromRecord($row);
        }
        return null;
    }

    public function getModuleGroup(string $id): ?ModuleGroup {
        $statement = $this->mysqlConnector->prepareStatement('SELECT * FROM module_groups WHERE id = ?');
        $statement->bind_param('i', $id);
        $result = $this->mysqlConnector->executeStatement($statement);
        while ($row = $result->fetch_assoc()) {
            return ModuleGroup::constructFromRecord($row);
        }
        return null;
    }

}
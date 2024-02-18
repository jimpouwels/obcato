<?php
require_once CMS_ROOT . "/core/model/Entity.php";
require_once CMS_ROOT . "/database/dao/ModuleDaoMysql.php";

class Module extends Entity {

    private string $_identifier;
    private bool $_popup;
    private bool $_enabled;
    private bool $_is_system_default;
    private int $_module_group_id;
    private string $_class;

    public static function constructFromRecord(array $row): Module {
        $module = new Module();
        $module->initFromDb($row);
        return $module;
    }

    protected function initFromDb(array $row): void {
        $this->setIdentifier($row['identifier']);
        $this->setPopUp($row['popup']);
        $this->setEnabled($row['enabled']);
        $this->setSystemDefault($row['system_default']);
        $this->setModuleGroupId($row['module_group_id']);
        $this->setClass($row['class']);
        parent::initFromDb($row);
    }

    public function setSystemDefault(bool $system_default): void {
        $this->_is_system_default = $system_default;
    }

    public function getClass(): string {
        return $this->_class;
    }

    public function setClass(string $class): void {
        $this->_class = $class;
    }

    public function getIdentifier(): string {
        return $this->_identifier;
    }

    public function setIdentifier(string $identifier): void {
        $this->_identifier = $identifier;
    }

    public function isPopUp(): bool {
        return $this->_popup;
    }

    public function setPopUp(bool $pop_up): void {
        $this->_popup = $pop_up;
    }

    public function isEnabled(): bool {
        return $this->_enabled;
    }

    public function setEnabled(bool $enabled): void {
        $this->_enabled = $enabled;
    }

    public function isSystemDefault(): bool {
        return $this->_is_system_default;
    }

    public function getModuleGroupId(): int {
        return $this->_module_group_id;
    }

    public function setModuleGroupId(int $module_group_id): void {
        $this->_module_group_id = $module_group_id;
    }

    public function getModuleGroup(): ModuleGroup {
        $module_dao = ModuleDaoMysql::getInstance();
        return $module_dao->getModuleGroup($this->_module_group_id);
    }

}
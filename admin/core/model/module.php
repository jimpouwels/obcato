<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/entity.php";

    class Module extends Entity {

        private string $_title_text_resource_identifier;
        private string $_icon_url;
        private string $_identifier;
        private bool $_popup;
        private bool $_enabled;
        private bool $_is_system_default;
        private int $_module_group_id;
        private string $_class;
        
        public function getTitleTextResourceIdentifier(): string {
            return $this->_title_text_resource_identifier;
        }
        
        public function setTitleTextResourceIdentifier(string $title_text_resource_identifier): void {
            $this->_title_text_resource_identifier = $title_text_resource_identifier;
        }
        
        public function getClass(): string {
            return $this->_class;
        }
        
        public function setClass(string $class): void {
            $this->_class = $class;
        }
        
        public function getIconUrl(): string {
            return $this->_icon_url;
        }
        
        public function setIconUrl(string $icon_url): void {
            $this->_icon_url = $icon_url;
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
        
        public function setEnabled(bool $enabled): void {
            $this->_enabled = $enabled;
        }
        
        public function isEnabled(): bool {
            return $this->_enabled;
        }
        
        public function setSystemDefault(bool $system_default): void {
            $this->_is_system_default = $system_default;
        }
    
        public function isSystemDefault(): bool {
            return $this->_is_system_default;
        }
        
        public function setModuleGroupId(int $module_group_id): void {
            $this->_module_group_id = $module_group_id;
        }
    
        public function getModuleGroupId(): int {
            return $this->_module_group_id;
        }
        
        public function getModuleGroup(): ModuleGroup {
            include_once CMS_ROOT . "database/dao/module_dao.php";
            $module_dao = ModuleDao::getInstance();
            $module_group = $module_dao->getModule($this->_module_group_id);
            return $module_group;
        }
        
        public static function constructFromRecord(array $record): Module {
            $module = null;
        
            $class = $record['class'];
            $identifier = $record['identifier'];
            if (!is_null($class) && $class != '') {
                $module = new Module();
                $module->setId($record['id']);
                $module->setTitleTextResourceIdentifier($record['title_text_resource_identifier']);
                $module->setIconUrl($record['icon_url']);
                $module->setIdentifier($identifier);
                $module->setPopUp($record['popup']);
                $module->setEnabled($record['enabled']);
                $module->setSystemDefault($record['system_default']);
                $module->setModuleGroupId($record['module_group_id']);
                $module->setClass($record['class']);
            }
            return $module;
        }

    }
    
?>
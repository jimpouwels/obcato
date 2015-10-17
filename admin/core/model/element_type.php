<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "core/model/presentable.php";
    require_once CMS_ROOT . "database/dao/scope_dao.php";

    class ElementType extends Entity {
    
        private $_name;
        private $_class_name;
        private $_domain_object;
        private $_icon_url;
        private $_identifier;
        private $_scope_id;
        private $_system_default;
        
        public function getName() {
            return $this->_name;
        }
        
        public function setName($name) {
            $this->_name = $name;
        }
        
        public function getClassName() {
            return $this->_class_name;
        }
        
        public function setClassName($class_name) {
            $this->_class_name = $class_name;
        }
        
        public function getRootDirectory() {
            return  "elements/" . $this->_identifier;
        }
        
        public function getIconUrl() {
            return $this->_icon_url;
        }
        
        public function setIconUrl($icon_url) {
            $this->_icon_url = $icon_url;
        }
        
        public function getIdentifier() {
            return $this->_identifier;
        }
        
        public function setIdentifier($identifier) {
            $this->_identifier = $identifier;
        }
        
        public function getDomainObject() {
            return $this->_domain_object;
        }
        
        public function setDomainObject($domain_object) {
            $this->_domain_object = $domain_object;
        }
        
        public function getScope() {
            $dao = ScopeDao::getInstance();
            return $dao->getScope($this->_scope_id);
        }
        
        public function getScopeId() {
            return $this->_scope_id;
        }
        
        public function setScopeId($scope_id) {
            $this->_scope_id = $scope_id;
        }
        
        public function getSystemDefault() {
            return $this->_system_default;
        }
        
        public function setSystemDefault($system_default) {
            $this->_system_default = $system_default;
        }
        
        public static function constructFromRecord($record) {
            $element_type = new ElementType();
            $element_type->setId($record['id']);
            $element_type->setName($record['name']);
            $element_type->setClassName($record['classname']);
            $element_type->setIconUrl($record['icon_url']);
            $element_type->setIdentifier($record['identifier']);
            $element_type->setDomainObject($record['domain_object']);
            $element_type->setScopeId($record['scope_id']);
            $element_type->setSystemDefault($record['system_default']);
            
            return $element_type;
        }
    
    }
    
?>
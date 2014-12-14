<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'utilities/file_utility.php';
    require_once CMS_ROOT . 'database/dao/element_dao.php';
    require_once CMS_ROOT . 'database/dao/scope_dao.php';
    require_once CMS_ROOT . 'core/data/element_type.php';
    require_once CMS_ROOT . 'core/data/scope.php';
    require_once CMS_ROOT . 'modules/components/installer/installer.php';

    abstract class ElementInstaller extends Installer {

        public static $CUSTOM_INSTALLER_CLASSNAME = 'CustomElementInstaller';
        private $_logger;
        private $_element_dao;
        private $_scope_dao;

        public function __construct($logger) {
            parent::__construct($logger);
            $this->_logger = $logger;
            $this->_element_dao = ElementDao::getInstance();
            $this->_scope_dao = ScopeDao::getInstance();
        }

        abstract function getName();
        abstract function getClassName();
        abstract function getClassFile();
        abstract function getScope();

        public function install() {
            $this->_logger->log('Installer voor component \'' . $this->getName() . '\' gestart');
            $this->installElementType();
            $this->installStaticFiles(STATIC_DIR . '/elements/' . $this->getIdentifier());
            $this->installBackendTemplates(BACKEND_TEMPLATE_DIR . '/elements/' . $this->getIdentifier());
            $this->installComponentFiles(CMS_ROOT . 'elements/' . $this->getIdentifier());
        }

        public function unInstall() {
            $this->_scope_dao->deleteScope($this->_scope_dao->getScopeByName($this->getScope()));
            $this->runUninstallQueries();
            $this->uninstallStaticFiles();
            $this->uninstallBackendTemplates();
            $this->uninstallComponentFiles();
        }

        private function installElementType() {
            $element_type = new ElementType();
            $element_type->setName($this->getName());
            $element_type->setIdentifier($this->getIdentifier());
            $element_type->setIconUrl($this->getIconPath());
            $element_type->setSystemDefault(false);
            $element_type->setClassName($this->getClassName());
            $element_type->setDomainObject($this->getClassFile());
            if (!$this->_element_dao->getElementTypeByIdentifier($this->getIdentifier())) {
                $element_type->setScopeId($this->createNewScope()->getId());
                $this->runInstallQueries();
                $this->_logger->log('Element wordt toegevoegd aan de database');
                $this->_element_dao->persistElementType($element_type);
            } else {
                $element_type->setScopeId($this->_scope_dao->getScopeByName($this->getScope())->getId());
                $this->_logger->log('Element database record wordt geupdate');
                $this->_element_dao->updateElementType($element_type);
            }
        }

        private function createNewScope() {
            $scope = new Scope();
            $scope->setName($this->getScope());
            $this->_scope_dao->persistScope($scope);
            return $scope;
        }

        private function uninstallStaticFiles() {
            FileUtility::recursiveDelete(STATIC_DIR . '/elements/' . $this->getIdentifier(), true);
        }

        private function uninstallBackendTemplates() {
            FileUtility::recursiveDelete(BACKEND_TEMPLATE_DIR . '/elements/' . $this->getIdentifier(), true);
        }

        private function uninstallComponentFiles() {
            FileUtility::recursiveDelete(CMS_ROOT . 'elements/' . $this->getIdentifier(), true);
        }
    }
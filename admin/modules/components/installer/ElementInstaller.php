<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . '/utilities/FileUtility.php';
require_once CMS_ROOT . '/database/dao/ElementDaoMysql.php';
require_once CMS_ROOT . '/database/dao/ScopeDaoMysql.php';
require_once CMS_ROOT . '/core/model/ElementType.php';
require_once CMS_ROOT . '/modules/templates/model/Scope.php';
require_once CMS_ROOT . '/modules/components/installer/Installer.php';

abstract class ElementInstaller extends Installer {

    public static string $CUSTOM_INSTALLER_CLASSNAME = 'CustomElementInstaller';
    private $_logger;
    private ElementDao $_element_dao;
    private ScopeDao $_scope_dao;

    public function __construct($logger) {
        parent::__construct($logger);
        $this->_logger = $logger;
        $this->_element_dao = ElementDaoMysql::getInstance();
        $this->_scope_dao = ScopeDaoMysql::getInstance();
    }

    abstract function getName(): string;

    abstract function getClassName(): string;

    abstract function getClassFile(): string;

    abstract function getScope(): string;

    public function install(): void {
        $this->_logger->log('Installer voor component \'' . $this->getName() . '\' gestart');
        $this->installElementType();
        $this->installStaticFiles(STATIC_DIR . '/elements/' . $this->getIdentifier());
        $this->installBackendTemplates(BACKEND_TEMPLATE_DIR . '/elements/' . $this->getIdentifier());
        $this->installComponentFiles(CMS_ROOT . 'elements/' . $this->getIdentifier());
    }

    public function uninstall(): void {
        $this->_scope_dao->deleteScope($this->_scope_dao->getScopeByIdentifier($this->getScope()));
        $this->runUninstallQueries();
        $this->uninstallStaticFiles();
        $this->uninstallBackendTemplates();
        $this->uninstallComponentFiles();
    }

    private function installElementType(): void {
        $element_type = new ElementType();
        $element_type->setIdentifier($this->getIdentifier());
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
            $element_type->setScopeId($this->_scope_dao->getScopeByIdentifier($this->getScope())->getId());
            $this->_logger->log('Element database record wordt geupdate');
            $this->_element_dao->updateElementType($element_type);
        }
    }

    private function createNewScope(): Scope {
        $scope = new Scope();
        $scope->setIdentifier($this->getScope());
        $this->_scope_dao->persistScope($scope);
        return $scope;
    }

    private function uninstallStaticFiles(): void {
        FileUtility::recursiveDelete(STATIC_DIR . '/elements/' . $this->getIdentifier(), true);
    }

    private function uninstallBackendTemplates(): void {
        FileUtility::recursiveDelete(BACKEND_TEMPLATE_DIR . '/elements/' . $this->getIdentifier(), true);
    }

    private function uninstallComponentFiles(): void {
        FileUtility::recursiveDelete(CMS_ROOT . 'elements/' . $this->getIdentifier(), true);
    }
}
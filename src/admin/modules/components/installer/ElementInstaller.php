<?php

namespace Obcato\Core\admin\modules\components\installer;

use FileUtility;
use Obcato\Core\admin\core\model\ElementType;
use Obcato\Core\admin\database\dao\ElementDao;
use Obcato\Core\admin\database\dao\ElementDaoMysql;
use Obcato\Core\admin\database\dao\ScopeDao;
use Obcato\Core\admin\database\dao\ScopeDaoMysql;
use Obcato\Core\admin\modules\templates\model\Scope;
use Obcato\Core\admin\view\views\ElementInstaller as IElementInstaller;

abstract class ElementInstaller extends Installer implements IElementInstaller {

    public static string $CUSTOM_INSTALLER_CLASSNAME = 'CustomElementInstaller';
    private Logger $logger;
    private ElementDao $elementDao;
    private ScopeDao $scopeDao;

    public function __construct($logger) {
        parent::__construct($logger);
        $this->logger = $logger;
        $this->elementDao = ElementDaoMysql::getInstance();
        $this->scopeDao = ScopeDaoMysql::getInstance();
    }

    public function install(): void {
        $this->logger->log('Installer voor component \'' . $this->getIdentifier() . '\' gestart');
        $this->installElementType();
        $this->installStaticFiles(STATIC_DIR . '/elements/' . $this->getIdentifier());
        $this->installBackendTemplates(BACKEND_TEMPLATE_DIR . '/elements/' . $this->getIdentifier());
        $this->installComponentFiles(CMS_ROOT . 'elements/' . $this->getIdentifier());
    }

    public function uninstall(): void {
        $this->scopeDao->deleteScope($this->scopeDao->getScopeByIdentifier($this->getScope()));
        $this->runUninstallQueries();
        $this->uninstallStaticFiles();
        $this->uninstallBackendTemplates();
        $this->uninstallComponentFiles();
    }

    private function installElementType(): void {
        $elementType = new ElementType();
        $elementType->setIdentifier($this->getIdentifier());
        $elementType->setSystemDefault(false);
        $elementType->setClassName($this->getClassName());
        $elementType->setDomainObject($this->getClassFile());
        if (!$this->elementDao->getElementTypeByIdentifier($this->getIdentifier())) {
            $elementType->setScopeId($this->createNewScope()->getId());
            $this->runInstallQueries();
            $this->logger->log('Element word toegevoegd aan de database');
            $this->elementDao->persistElementType($elementType);
        } else {
            $elementType->setScopeId($this->scopeDao->getScopeByIdentifier($this->getScope())->getId());
            $this->logger->log('Element database record wordt geupdate');
            $this->elementDao->updateElementType($elementType);
        }
    }

    private function createNewScope(): Scope {
        $scope = new Scope();
        $scope->setIdentifier($this->getScope());
        $this->scopeDao->persistScope($scope);
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
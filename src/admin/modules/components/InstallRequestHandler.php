<?php

namespace Obcato\Core\admin\modules\components;

use FileUtility;
use Obcato\Core\admin\core\form\FormException;
use Obcato\Core\admin\modules\components\installer\InstallationException;
use Obcato\Core\admin\modules\components\installer\Logger;
use Obcato\Core\admin\modules\components\installer\ModuleInstaller;
use Obcato\Core\admin\request_handlers\HttpRequestHandler;
use ZipArchive;

class InstallRequestHandler extends HttpRequestHandler {

    private Logger $logger;

    public function __construct() {
        $this->logger = new Logger();
    }

    public function handleGet(): void {}

    public function handlePost(): void {
        if ($this->isInstallComponentAction())
            $this->installComponent();
    }

    private function isInstallComponentAction(): bool {
        return isset($_POST['action']) && $_POST['action'] == 'install_component';
    }

    private function installComponent(): void {
        $form = new InstallComponentForm();
        try {
            $form->loadFields();
            $this->handleComponentZip($form->getFilePath());
        } catch (FormException $e) {
            $this->sendErrorMessage('U dient een component archief te kiezen');
        } catch (InstallationException $e) {
            $this->sendErrorMessage('Installatie van component mislukt');
        }
    }

    private function handleComponentZip($filePath): void {
        $zipArchive = new ZipArchive();
        $zip = $zipArchive->open($filePath);
        try {
            $this->checkIfFileIsZip($zip);
            $this->logger->log('ZIP archief gevonden');
            $this->extractZip($zipArchive);
            $this->runInstaller();
        } finally {
            $zipArchive->close();
            $this->logger->log('Tijdelijke bestanden opruimen');
            FileUtility::recursiveDelete(COMPONENT_TEMP_DIR);
        }
        $this->logger->log('Installatie succesvol afgerond');
    }

    private function checkIfFileIsZip($zip): void {
        if (is_numeric($zip)) {
            $this->logger->log('Invalide ZIP archief');
            throw new InstallationException();
        }
    }

    private function extractZip($zipArchive): void {
        if (!file_exists(COMPONENT_TEMP_DIR)) mkdir(COMPONENT_TEMP_DIR);
        $this->logger->log('ZIP archief uitpakken naar ' . COMPONENT_TEMP_DIR);
        $zipArchive->extractTo(COMPONENT_TEMP_DIR);
    }

    private function runInstaller(): void {
        $this->checkInstallerFileProvided();
        require_once COMPONENT_TEMP_DIR . '/installer.php';
        $installer = null;
        if ($this->uploadedFileIs(ModuleInstaller::$CUSTOM_INSTALLER_CLASSNAME))
            $installer = $this->getModuleInstaller();
        else if ($this->uploadedFileIs(ElementInstaller::$CUSTOM_INSTALLER_CLASSNAME))
            $installer = $this->getElementInstaller();
        else {
            $this->logger->log('Er is geen geldige installer implementatie gevonden');
            throw new InstallationException();
        }
        $this->logger->log('Installer uitvoeren');
        $installer->install();
    }

    private function checkInstallerFileProvided(): void {
        if (!file_exists(COMPONENT_TEMP_DIR . '/installer.php')) {
            $this->logger->log('installer.php bestand niet gevonden');
            throw new InstallationException();
        }
        $this->logger->log('installer.php bestand gevonden');
    }

    private function uploadedFileIs($installerClassname) {
        if (class_exists($installerClassname)) {
            $this->logger->log($installerClassname . ' class gevonden');
            return true;
        }
        return false;
    }

    private function getModuleInstaller() {
        $installer = new CustomModuleInstaller($this->logger);
        $this->isModuleInstaller($installer);
        return $installer;
    }

    private function isModuleInstaller($installer) {
        if (!$installer instanceof ModuleInstaller) {
            $this->logger->log('Installer class moet een implementatie zijn van ModuleInstaller');
            throw new InstallationException();
        }
    }

    private function getElementInstaller() {
        $installer = new CustomElementInstaller($this->logger);
        $this->isElementInstaller($installer);
        return $installer;
    }

    private function isElementInstaller($installer) {
        if (!$installer instanceof ElementInstaller) {
            $this->logger->log('Installer class moet een implementatie zijn van ElementInstaller');
            throw new InstallationException();
        }
    }

    public function getLogMessages() {
        return $this->logger->getMessages();
    }
}
<?php
require_once CMS_ROOT . '/database/MysqlConnector.php';
require_once CMS_ROOT . '/modules/components/installer/Installer.php';
require_once CMS_ROOT . '/utilities/FileUtility.php';

abstract class Installer {

    private MysqlConnector $mysqlConnector;
    private Logger $logger;
    private static string $STATIC_DIR = 'static';
    private static string $TEMPLATE_DIR = 'templates';
    private static string $TEXT_RESOURCE_DIR = 'text_resources';

    public function __construct($logger) {
        $this->logger = $logger;
        $this->mysqlConnector = MysqlConnector::getInstance();
    }

    abstract function getInstallQueries(): array;

    abstract function getUninstallQueries(): array;

    abstract function getIdentifier(): string;

    protected function runInstallQueries(): void {
        $this->logger->log('Installatiequeries uitvoeren');
        $queries = $this->getInstallQueries();
        if (!$queries) return;
        foreach ($queries as $query) {
            $this->logger->log('Query uitvoeren: ' . $query);
            $this->mysqlConnector->executeQuery($query);
        }
    }

    protected function runUninstallQueries(): void {
        $uninstallQueries = $this->getUninstallQueries();
        if (!$uninstallQueries) return;
        foreach ($uninstallQueries as $query) {
            $this->mysqlConnector->executeQuery($query);
        }
    }

    protected function installStaticFiles($targetDir): void {
        $sourceDir = COMPONENT_TEMP_DIR . '/' . self::$STATIC_DIR;
        if (file_exists($sourceDir)) {
            $this->createDir($targetDir);
            $this->logger->log('Statische bestanden kopiëren naar ' . $targetDir);
            FileUtility::moveDirectoryContents($sourceDir, $targetDir, true);
        } else {
            $this->logger->log('Geen statische bestanden gevonden');
        }
    }

    protected function installTextResources($targetDir): void {
        $sourceDir = COMPONENT_TEMP_DIR . '/' . self::$TEXT_RESOURCE_DIR;
        if (file_exists($sourceDir)) {
            $this->logger->log('Text resource bestanden kopiëren naar ' . $targetDir);
            FileUtility::moveDirectoryContents($sourceDir, $targetDir, true);
        } else {
            $this->logger->log('Geen text resources bestanden gevonden');
        }
    }

    protected function installBackendTemplates($targetDir): void {
        $resourceDir = COMPONENT_TEMP_DIR . '/' . self::$TEMPLATE_DIR;
        if (file_exists($resourceDir)) {
            $this->createDir($targetDir);
            $this->logger->log('Backend templates kopiëren naar ' . $targetDir);
            FileUtility::moveDirectoryContents($resourceDir, $targetDir, true);
        } else {
            $this->logger->log('Geen backend templates gevonden');
        }
    }

    protected function installComponentFiles($targetDir): void {
        $this->createDir($targetDir);
        $this->logger->log('Overige bestanden kopiëren naar ' . $targetDir);
        FileUtility::moveDirectoryContents(COMPONENT_TEMP_DIR, $targetDir);
    }

    protected function createDir($targetDir): void {
        if (file_exists($targetDir)) {
            FileUtility::recursiveDelete($targetDir);
        } else {
            mkdir($targetDir);
        }
    }

    protected function uninstallTextResources(): void {
        FileUtility::deleteFilesStartingWith(STATIC_DIR . '/text_resources', $this->getIdentifier());
    }

}
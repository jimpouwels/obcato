<?php

namespace Obcato\Core\admin\modules\components\installer;

use Obcato\Core\admin\database\MysqlConnector;
use Obcato\Core\admin\utilities\FileUtility;
use const Obcato\Core\admin\COMPONENT_TEMP_DIR;
use const Obcato\Core\admin\STATIC_DIR;

abstract class Installer {

    private MysqlConnector $mysqlConnector;
    private Logger $logger;

    public function __construct($logger) {
        $this->logger = $logger;
        $this->mysqlConnector = MysqlConnector::getInstance();
    }

    public abstract function getInstallQueries(): array;

    public abstract function getUninstallQueries(): array;

    public abstract function getIdentifier(): string;

    public abstract function getPackageStaticDir(): string;

    public abstract function getPackageTemplateDir(): string;

    public abstract function getPackageTextResourceDir(): string;

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
        $sourceDir = COMPONENT_TEMP_DIR . '/' . $this->getPackageStaticDir();
        if (file_exists($sourceDir)) {
            $this->createDir($targetDir);
            $this->logger->log('Statische bestanden kopiëren naar ' . $targetDir);
            FileUtility::moveDirectoryContents($sourceDir, $targetDir, true);
        } else {
            $this->logger->log('Geen statische bestanden gevonden');
        }
    }

    protected function installTextResources($targetDir): void {
        $sourceDir = COMPONENT_TEMP_DIR . '/' . $this->getPackageTextResourceDir();
        if (file_exists($sourceDir)) {
            $this->logger->log('Text resource bestanden kopiëren naar ' . $targetDir);
            FileUtility::moveDirectoryContents($sourceDir, $targetDir, true);
        } else {
            $this->logger->log('Geen text resources bestanden gevonden');
        }
    }

    protected function installBackendTemplates($targetDir): void {
        $resourceDir = COMPONENT_TEMP_DIR . '/' . $this->getPackageTemplateDir();
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
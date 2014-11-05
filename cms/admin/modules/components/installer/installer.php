<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'database/mysql_connector.php';
    require_once CMS_ROOT . 'modules/components/installer/installer.php';
    require_once CMS_ROOT . 'utilities/file_utility.php';

    abstract class Installer {

        private $_mysql_connector;
        private $_logger;
        private static $STATIC_DIR = 'static';
        private static $TEMPLATE_DIR = 'templates';

        public function __construct($logger) {
            $this->_logger = $logger;
            $this->_mysql_connector = MysqlConnector::getInstance();
        }

        abstract function getInstallQueries();
        abstract function getUninstallQueries();
        abstract function getIconPath();

        protected function runInstallQueries() {
            $this->_logger->log('Installtiequeries uitvoeren');
            $queries = $this->getInstallQueries();
            if (!is_array($queries)) return;
            foreach ($queries as $query) {
                $this->_logger->log('Query uitvoeren: ' . $query);
                $this->_mysql_connector->executeQuery($query);
            }
        }

        protected function runUninstallQueries() {
            $uninstall_queries = $this->getUninstallQueries();
            if (!is_array($uninstall_queries)) return;
            foreach ($uninstall_queries as $query)
                $this->_mysql_connector->executeQuery($query);
        }

        protected function installStaticFiles($target_dir) {
            $source_dir = COMPONENT_TEMP_DIR . '/' . self::$STATIC_DIR;
            if (self::$STATIC_DIR && file_exists($source_dir)) {
                $this->createDir($target_dir);
                $this->_logger->log('Statische bestanden kopiëren naar ' . $target_dir);
                FileUtility::moveDirectoryContents($source_dir, $target_dir, true);
            }
            else
                $this->_logger->log('Geen statische bestanden gevonden');
        }

        protected function installBackendTemplates($target_dir) {
            $source_dir = COMPONENT_TEMP_DIR . '/' . self::$TEMPLATE_DIR;
            if (self::$TEMPLATE_DIR && file_exists($source_dir)) {
                $this->createDir($target_dir);
                $this->_logger->log('Backend templates kopiëren naar ' . $target_dir);
                FileUtility::moveDirectoryContents($source_dir, $target_dir, true);
            } else
                $this->_logger->log('Geen backend templates gevonden');
        }

        protected function installComponentFiles($target_dir) {
            $this->createDir($target_dir);
            $this->_logger->log('Overige bestanden kopiëren naar ' . $target_dir);
            FileUtility::moveDirectoryContents(COMPONENT_TEMP_DIR, $target_dir);
        }

        protected function createDir($target_dir) {
            if (file_exists($target_dir))
                FileUtility::recursiveDelete($target_dir);
            else
                mkdir($target_dir);
        }

    }
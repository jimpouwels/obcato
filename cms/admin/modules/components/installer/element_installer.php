<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'utilities/file_utility.php';
    require_once CMS_ROOT . 'modules/components/installer/installer.php';

    abstract class ElementInstaller extends Installer {

        public static $CUSTOM_INSTALLER_CLASSNAME = 'CustomElementInstaller';
        private $_logger;

        public function __construct($logger) {
            parent::__construct($logger);
            $this->_logger = $logger;
        }

        abstract function getIdentifier();
        abstract function getName();
        abstract function getClassName();
        abstract function getClassFile();
        abstract function getScope();

        public function install() {
            $this->_logger->log('Installer voor component \'' . $this->getName() . '\' gestart');
        }

        public function unInstall() {

        }

    }
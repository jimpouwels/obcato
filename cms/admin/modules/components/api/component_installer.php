<?php
    defined('_ACCESS') or die;

    abstract class ComponentInstaller {

        abstract function getIdentifier();
        abstract function getTitle();

        public function install() {
            echo 'Installing ' . $this->getTitle();
        }

    }
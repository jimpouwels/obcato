<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "/view/views/module_visual.php";

    class ComponentsModuleVisual extends ModuleVisual {

        public function getActionButtons() {
            return array();
        }

        public function getHeadIncludes() {
        }

        public function getRequestHandlers() {
            return array();
        }

        public function onPreHandled() {
        }

        public function getTitle() {
            return "Componenten";
        }

        public function render() {
        }
    }
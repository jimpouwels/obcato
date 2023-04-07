<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/template_engine.php";
    require_once CMS_ROOT . "core/blackboard.php";
    
    abstract class Visual {
        
        private Smarty $_template_engine;

        public function __construct() {
            $this->_template_engine = TemplateEngine::getInstance();
        }

        public function render(): string {
            $this->_template_engine->assign("backend_base_url", $this->getBackendBaseUrl());
            $this->_template_engine->assign("backend_base_url_raw", $this->getBackendBaseUrlRaw());
            return $this->renderVisual();
        }

        abstract function renderVisual(): string;

        protected function getTemplateEngine(): Smarty {
            return $this->_template_engine;
        }

        protected function getTextResource($identifier): string {
            return Session::getTextResource($identifier);
        }

        protected function getBackendBaseUrl(): string {
            return BlackBoard::getBackendBaseUrl();
        }
        
        protected function getBackendBaseUrlRaw(): string {
            return BlackBoard::getBackendBaseUrlRaw();
        }

        protected function getBackendBaseUrlWithoutTab(): string {
            return BlackBoard::getBackendBaseUrlWithoutTab();
        }

        protected function getCurrentTabId(): int {
            return BlackBoard::$MODULE_TAB_ID;
        }
    }
<?php
    defined('_ACCESS') or die;
    
    abstract class ModuleVisual extends Visual {

        private $_module;

        protected function __construct($module) {
            parent::__construct();
            $this->_module = $module;
        }

        public function getTitle() {
            return $this->getTextResource($this->_module->getTitleTextResourceIdentifier());
        }
        
        abstract function getActionButtons();
        
        abstract function renderHeadIncludes();
        
        abstract function getRequestHandlers();
        
        public function onRequestHandled(): void {
    
    }
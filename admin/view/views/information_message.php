<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/visual.php";
    
    class InformationMessage extends Visual {
    
        private static $TEMPLATE = "system/information_message.tpl";
        private $_message;
        private $_template_engine;
    
        public function __construct($message) {
            parent::__construct();
            $this->_template_engine = TemplateEngine::getInstance();
            $this->_message = $message;
        }
    
        public function renderVisual(): string {
            $this->_template_engine->assign("message", $this->_message);
            
            return $this->_template_engine->fetch(self::$TEMPLATE);
        }
    }
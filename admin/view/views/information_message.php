<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/visual.php";
    
    class InformationMessage extends Visual {
    
        private static $TEMPLATE = "system/information_message.tpl";
        private $_message;
    
        public function __construct($message) {
            parent::__construct();
            $this->_message = $message;
        }
    
        public function renderVisual(): string {
            $this->getTemplateEngine()->assign("message", $this->_message);
            return $this->getTemplateEngine()->fetch(self::$TEMPLATE);
        }
    }
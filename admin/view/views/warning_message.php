<?php
    defined('_ACCESS') or die;
    
    class WarningMessage extends Visual {
    
        private static $TEMPLATE = "system/warning_message.tpl";
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

?>
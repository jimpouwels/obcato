<?php
    defined('_ACCESS') or die;
    
    class WarningMessage extends Visual {
    
        private $_message;
    
        public function __construct(string $message) {
            parent::__construct();
            $this->_message = $message;
        }

        public function getTemplateFilename(): string {
            return "system/warning_message.tpl";
        }
    
        public function load(): void {
            $this->assign("message", $this->_message);
        }    
    }

?>
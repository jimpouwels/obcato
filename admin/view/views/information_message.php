<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/visual.php";
    
    class InformationMessage extends Visual {
    
        private static string $TEMPLATE = "system/information_message.tpl";
        private string $_message;
    
        public function __construct(string $message) {
            parent::__construct();
            $this->_message = $message;
        }
    
        public function render(): string {
            $this->getTemplateEngine()->assign("message", $this->_message);
            return $this->getTemplateEngine()->fetch(self::$TEMPLATE);
        }
    }
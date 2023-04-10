<?php

    
    defined('_ACCESS') or die;
    
    class Button extends Visual {
        
        private static $TEMPLATE = "system/button.tpl";
        private $_id;
        private $_label;
        private $_onclick;
        
        public function __construct($id, $label, $onclick) {
            parent::__construct();
            $this->_id = $id;
            $this->_label = $label;
            $this->_onclick = $onclick;
        }
        
        public function render(): string {
            $this->getTemplateEngine()->assign("id", $this->_id);
            $this->getTemplateEngine()->assign("label", $this->_label);
            $this->getTemplateEngine()->assign("onclick", $this->_onclick);
            
            return $this->getTemplateEngine()->fetch(self::$TEMPLATE);
        }
        
    }

?>
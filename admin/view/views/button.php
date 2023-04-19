<?php

    
    defined('_ACCESS') or die;
    
    class Button extends Visual {
        
        private static string $TEMPLATE = "system/button.tpl";
        private ?string $_id = null;
        private string $_label;
        private ?string $_onclick = null;
        
        public function __construct(?string $id, string $label, ?string $onclick) {
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
<?php

    
    defined('_ACCESS') or die;
    
    class Button extends Visual {
        
        private ?string $_id = null;
        private string $_label;
        private ?string $_onclick = null;
        
        public function __construct(?string $id, string $label, ?string $onclick) {
            parent::__construct();
            $this->_id = $id;
            $this->_label = $label;
            $this->_onclick = $onclick;
        }

        public function getTemplateFilename(): string {
            return "system/button.tpl";
        }
        
        public function load(): void {
            $this->assign("id", $this->_id);
            $this->assign("label", $this->_label);
            $this->assign("onclick", $this->_onclick);
        }
        
    }

?>
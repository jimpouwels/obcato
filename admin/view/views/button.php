<?php

    
    defined('_ACCESS') or die;
    
    class Button extends Visual {
        
        private static $TEMPLATE = "system/button.tpl";
        private $_id;
        private $_label;
        private $_onclick;
        private $_template_engine;
        
        public function __construct($id, $label, $onclick) {
            $this->_id = $id;
            $this->_label = $label;
            $this->_onclick = $onclick;
            $this->_template_engine = TemplateEngine::getInstance();
        }
        
        public function render(): string {
            $this->_template_engine->assign("id", $this->_id);
            $this->_template_engine->assign("label", $this->_label);
            $this->_template_engine->assign("onclick", $this->_onclick);
            
            return $this->_template_engine->fetch(self::$TEMPLATE);
        }
        
    }

?>
<?php

    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/visual.php";

    class ListElementStatics extends Visual {
    
        private static $TEMPLATE = "elements/list_element/list_element_statics.tpl";
        
        private $_template_engine;
    
        public function __construct() {
            $this->_template_engine = TemplateEngine::getInstance();
        }
        
        public function render(): string {
            return $this->_template_engine->fetch(self::$TEMPLATE);
        }
    
    }
    
?>
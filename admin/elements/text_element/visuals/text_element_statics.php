<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/visual.php";

    class TextElementStatics extends Visual {

        private static $TEMPLATE = "elements/text_element/text_element_statics.tpl";
        
        private $_template_engine;
    
        public function __construct() {
            parent::__construct();
            $this->_template_engine = TemplateEngine::getInstance();
        }
        
        public function renderVisual(): string {
            return $this->_template_engine->fetch(self::$TEMPLATE);
        }
    
    }
?>
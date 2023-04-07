<?php
    
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/visual.php";

    class TableOfContentsElementStatics extends Visual {
    
        private static $TEMPLATE = "elements/table_of_contents_element/table_of_contents_element_statics.tpl";
        
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
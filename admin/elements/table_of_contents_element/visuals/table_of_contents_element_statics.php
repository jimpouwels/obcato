<?php
    
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/visual.php";

    class TableOfContentsElementStatics extends Visual {
    
        private static string $TEMPLATE = "elements/table_of_contents_element/table_of_contents_element_statics.tpl";
        
        public function __construct() {
            parent::__construct();
        }
        
        public function render(): string {
            return $this->getTemplateEngine()->fetch(self::$TEMPLATE);
        }
    
    }
    
?>
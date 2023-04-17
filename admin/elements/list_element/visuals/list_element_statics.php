<?php

    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/visual.php";

    class ListElementStatics extends Visual {
    
        private static string $TEMPLATE = "elements/list_element/list_element_statics.tpl";
    
        public function __construct() {
            parent::__construct();
        }
        
        public function render(): string {
            return $this->getTemplateEngine()->fetch(self::$TEMPLATE);
        }
    
    }
    
?>
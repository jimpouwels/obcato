<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/visual.php";

    class TextElementStatics extends Visual {

        private static string $TEMPLATE = "elements/text_element/text_element_statics.tpl";
    
        public function __construct() {
            parent::__construct();
        }
        
        public function render(): string {
            return $this->getTemplateEngine()->fetch(self::$TEMPLATE);
        }
    
    }
?>
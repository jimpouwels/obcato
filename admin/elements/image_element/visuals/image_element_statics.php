<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/visual.php";

    class ImageElementStatics extends Visual {
    
        private static $TEMPLATE = "elements/image_element/image_element_statics.tpl";
    
        public function __construct() {
            parent::__construct();
        }
        
        public function render(): string {
            return $this->getTemplateEngine()->fetch(self::$TEMPLATE);
        }
    
    }
    
?>
<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/visual.php";

    class TextElementStatics extends Visual {
    
        public function __construct() {
            parent::__construct();
        }
        
        public function getTemplateFilename(): string {
            return "elements/text_element/text_element_statics.tpl";
        }

        public function load(): void {
        }
    
    }
?>
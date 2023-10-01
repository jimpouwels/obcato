<?php
    defined('_ACCESS') or die;
    
    class IFrameElementStatics extends Visual {
    
        public function __construct() {
            parent::__construct();
        }

        public function getTemplateFilename(): string {
            return "elements/iframe_element/iframe_element_statics.tpl";
        }
        
        public function load(): void {
        }
    
    }
    
?>
<?php
    
    defined('_ACCESS') or die;
    
    class TableOfContentsElementStatics extends Visual {
    
        public function __construct() {
            parent::__construct();
        }
        
        public function getTemplateFilename(): string {
            return "elements/table_of_contents_element/table_of_contents_element_statics.tpl";
        }

        public function load(): void {
        }
    
    }
    
?>
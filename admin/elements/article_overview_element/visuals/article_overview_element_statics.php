<?php
    
    defined('_ACCESS') or die;
    
    class ArticleOverviewElementStatics extends Visual {
    
        public function __construct() {
            parent::__construct();
        }

        public function getTemplateFilename(): string {
            return "elements/article_overview_element/article_overview_element_statics.tpl";
        }
        
        public function load(): void {
        }
    
    }
    
?>
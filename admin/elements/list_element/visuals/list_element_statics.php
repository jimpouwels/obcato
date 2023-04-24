<?php

    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/visual.php";

    class ListElementStatics extends Visual {
    
        public function __construct() {
            parent::__construct();
        }

        public function getTemplateFilename(): string {
            return "elements/list_element/list_element_statics.tpl";
        }

        public function load(): void {
        }
    
    }
    
?>
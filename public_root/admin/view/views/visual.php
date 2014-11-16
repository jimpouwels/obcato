<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/template_engine.php";
    require_once CMS_ROOT . "text_resource_loader.php";
    
    abstract class Visual {
        
        abstract function render();

        protected function getTextResource($identifier) {
            return TextResourceLoader::getTextResource($identifier);
        }
    }

?>
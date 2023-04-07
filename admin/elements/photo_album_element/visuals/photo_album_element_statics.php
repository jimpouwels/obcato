<?php
    
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/visual.php";

    class PhotoAlbumElementStatics extends Visual {
    
        private static $TEMPLATE = "elements/photo_album_element/photo_album_element_statics.tpl";
    
        public function __construct() {
            parent::__construct();
        }
        
        public function renderVisual(): string {
            return $this->getTemplateEngine()->fetch(self::$TEMPLATE);
        }
    
    }
    
?>
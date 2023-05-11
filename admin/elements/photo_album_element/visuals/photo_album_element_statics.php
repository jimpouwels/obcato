<?php
    
    defined('_ACCESS') or die;
    
    class PhotoAlbumElementStatics extends Visual {
    
        public function __construct() {
            parent::__construct();
        }
        
        public function getTemplateFilename(): string {
            return "elements/photo_album_element/photo_album_element_statics.tpl";
        }

        public function load(): void {
        }
    
    }
    
?>
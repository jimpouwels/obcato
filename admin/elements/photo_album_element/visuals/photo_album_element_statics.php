<?php
    
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/visual.php";

    class PhotoAlbumElementStatics extends Visual {
    
        private static $TEMPLATE = "elements/photo_album_element/photo_album_element_statics.tpl";
        
        private $_template_engine;
    
        public function __construct() {
            $this->_template_engine = TemplateEngine::getInstance();
        }
        
        public function render(): string {
            return $this->_template_engine->fetch(self::$TEMPLATE);
        }
    
    }
    
?>
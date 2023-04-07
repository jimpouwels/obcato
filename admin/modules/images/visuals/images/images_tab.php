<?php
    
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "modules/images/visuals/images/image_search.php";
    require_once CMS_ROOT . "modules/images/visuals/images/image_list.php";
    require_once CMS_ROOT . "modules/images/visuals/images/image_editor.php";
    
    class ImagesTab extends Visual {
    
        private static $TEMPLATE = "images/images/root.tpl";
    
        private $_current_image;
        private $_images_pre_handler;
    
        public function __construct($images_pre_handler) {
            parent::__construct();
            $this->_images_pre_handler = $images_pre_handler;
            $this->_current_image = $this->_images_pre_handler->getCurrentImage();
        }
    
        public function renderVisual(): string {
            $this->getTemplateEngine()->assign("search", $this->renderImageSearch());
            if (!is_null($this->_current_image)) {
                $this->getTemplateEngine()->assign("editor", $this->renderImageEditor());
            } else {
                $this->getTemplateEngine()->assign("list", $this->renderImageList());
            }
            return $this->getTemplateEngine()->fetch("modules/" . self::$TEMPLATE);
        }
        
        private function renderImageSearch() {
            $image_search = new ImageSearch($this->_images_pre_handler);
            return $image_search->render();
        }
        
        private function renderImageList() {
            $images_list = new ImageList($this->_current_image, $this->_images_pre_handler);
            return $images_list->render();
        }
        
        private function renderImageEditor() {
            $image_editor = new ImageEditor($this->_current_image);
            return $image_editor->render();
        }
    
    }
    
?>
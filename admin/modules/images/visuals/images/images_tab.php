<?php
    
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "modules/images/visuals/images/image_search.php";
    require_once CMS_ROOT . "modules/images/visuals/images/image_list.php";
    require_once CMS_ROOT . "modules/images/visuals/images/image_editor.php";
    
    class ImagesTab extends Visual {
    
        private $_current_image;
        private $_images_pre_handler;
    
        public function __construct($images_pre_handler) {
            parent::__construct();
            $this->_images_pre_handler = $images_pre_handler;
            $this->_current_image = $this->_images_pre_handler->getCurrentImage();
        }

        public function getTemplateFilename(): string {
            return "modules/images/images/root.tpl";
        }
    
        public function load(): void {
            $this->assign("search", $this->renderImageSearch());
            if (!is_null($this->_current_image)) {
                $this->assign("editor", $this->renderImageEditor());
            } else {
                $this->assign("list", $this->renderImageList());
            }
        }
        
        private function renderImageSearch(): string {
            $image_search = new ImageSearch($this->_images_pre_handler);
            return $image_search->render();
        }
        
        private function renderImageList(): string {
            $images_list = new ImageList($this->_current_image, $this->_images_pre_handler);
            return $images_list->render();
        }
        
        private function renderImageEditor(): string {
            $image_editor = new ImageEditor($this->_current_image);
            return $image_editor->render();
        }
    
    }
    
?>
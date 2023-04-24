<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/search.php";
    
    class Popup extends Visual {
        
        private string $_popup_type;
        
        public function __construct(string $popup_type) {
            parent::__construct();
            $this->_popup_type = $popup_type;
        }

        public function getTemplateFilename(): string {
            return "system/popup.tpl";
        }
        
        public function load(): void {
            $content = null;
            if ($this->_popup_type == "search") {
                $content = new Search();
            }
            $this->assign("content", $content->render());
        }
        
    }
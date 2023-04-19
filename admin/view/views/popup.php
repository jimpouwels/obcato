<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/search.php";
    
    class Popup extends Visual {
        
        private static string $TEMPLATE = "system/popup.tpl";
        private string $_popup_type;
        
        public function __construct(string $popup_type) {
            parent::__construct();
            $this->_popup_type = $popup_type;
        }
        
        public function render(): string {
            $content = null;
            if ($this->_popup_type == "search") {
                $content = new Search();
            }
            $this->getTemplateEngine()->assign("content", $content->render());
            return $this->getTemplateEngine()->fetch(self::$TEMPLATE);
        }
        
    }
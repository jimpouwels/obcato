<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/element_holder_search.php";
    require_once CMS_ROOT . "view/views/image_search_box.php";
    
    class Search extends Visual {
        
        public static $ARTICLES = "articles";
        public static $PAGES = "pages";
        public static $IMAGES = "images";
        public static $ELEMENT_HOLDERS = "element_holders";
        
        public static $BACK_CLICK_ID_KEY = "back_click_id";
        public static $BACKFILL_KEY = "backfill";
        public static $OBJECT_TO_SEARCH_KEY = "object";
        public static $POPUP_TYPE_KEY = "popup";
        
        private $_template_engine;
        private $_back_click_id;
        private $_backfill_id;
        private $_objects_to_search;
        private $_popup_type;
        
        public function __construct() {
            $this->_template_engine = TemplateEngine::getInstance();
            $this->_back_click_id = $_GET[self::$BACK_CLICK_ID_KEY];
            $this->_backfill_id = $_GET[self::$BACKFILL_KEY];
            $this->_objects_to_search = $_GET[self::$OBJECT_TO_SEARCH_KEY];
            $this->_popup_type = $_GET[self::$POPUP_TYPE_KEY];
        }
        
        public function render(): string {
            $search = null;
            if ($_GET[self::$OBJECT_TO_SEARCH_KEY] == self::$IMAGES)
                $search = new ImageSearchBox($this->_back_click_id, $this->_backfill_id, $this->_objects_to_search);
            else
                $search = new ElementHolderSearch($this->_back_click_id, $this->_backfill_id, $this->_objects_to_search);
            $this->_template_engine->assign("popup_type", $this->_popup_type);
            return $search->render();
        }
        
    }

?>
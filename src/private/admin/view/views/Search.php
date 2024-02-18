<?php
require_once CMS_ROOT . "/view/views/ElementHolderSearch.php";
require_once CMS_ROOT . "/view/views/ImageSearchBox.php";

class Search extends Visual {

    public static string $ARTICLES = "articles";
    public static string $PAGES = "pages";
    public static string $IMAGES_POPUP_TYPE = "images";
    public static string $ELEMENT_HOLDERS = "element_holders";
    public static string $BACK_CLICK_ID_KEY = "back_click_id";
    public static string $BACKFILL_KEY = "backfill";
    public static string $OBJECT_TO_SEARCH_KEY = "object";
    public static string $POPUP_TYPE_KEY = "popup";
    private string $_back_click_id;
    private string $_backfill_id;
    private string $_objects_to_search;
    private string $_popup_type;

    public function __construct() {
        parent::__construct();
        $this->_back_click_id = $_GET[self::$BACK_CLICK_ID_KEY];
        $this->_backfill_id = $_GET[self::$BACKFILL_KEY];
        $this->_objects_to_search = $_GET[self::$OBJECT_TO_SEARCH_KEY];
        $this->_popup_type = $_GET[self::$POPUP_TYPE_KEY];
    }

    public function getTemplateFilename(): string {
        return "system/popup_search.tpl";
    }

    public function load(): void {
        if ($_GET[self::$OBJECT_TO_SEARCH_KEY] == self::$IMAGES_POPUP_TYPE) {
            $search = new ImageSearchBox($this->_back_click_id, $this->_backfill_id, $this->_objects_to_search, $this->_popup_type);
        } else {
            $search = new ElementHolderSearch($this->_back_click_id, $this->_backfill_id, $this->_objects_to_search, $this->_popup_type);
        }
        $this->assign("search_content", $search->render());
    }

}
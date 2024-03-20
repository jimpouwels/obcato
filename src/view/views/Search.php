<?php

namespace Obcato\Core\view\views;

class Search extends Visual {

    public static string $ARTICLES = "articles";
    public static string $PAGES = "pages";
    public static string $IMAGES_POPUP_TYPE = "images";
    public static string $ELEMENT_HOLDERS = "element_holders";
    public static string $BACK_CLICK_ID_KEY = "back_click_id";
    public static string $BACKFILL_KEY = "backfill";
    public static string $OBJECT_TO_SEARCH_KEY = "object";
    public static string $POPUP_TYPE_KEY = "popup";
    private string $backClickId;
    private string $backfillId;
    private string $objectsToSearch;
    private string $popupType;

    public function __construct() {
        parent::__construct();
        $this->backClickId = $_GET[self::$BACK_CLICK_ID_KEY];
        $this->backfillId = $_GET[self::$BACKFILL_KEY];
        $this->objectsToSearch = $_GET[self::$OBJECT_TO_SEARCH_KEY];
        $this->popupType = $_GET[self::$POPUP_TYPE_KEY];
    }

    public function getTemplateFilename(): string {
        return "system/popup_search.tpl";
    }

    public function load(): void {
        if ($_GET[self::$OBJECT_TO_SEARCH_KEY] == self::$IMAGES_POPUP_TYPE) {
            $search = new ImageSearchBox($this->backClickId, $this->backfillId, $this->objectsToSearch, $this->popupType);
        } else {
            $search = new ElementHolderSearch($this->backClickId, $this->backfillId, $this->objectsToSearch, $this->popupType);
        }
        $this->assign("search_content", $search->render());
    }

}
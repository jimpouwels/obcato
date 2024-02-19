<?php
require_once CMS_ROOT . "/view/views/Search.php";
require_once CMS_ROOT . "/view/views/InformationMessage.php";
require_once CMS_ROOT . "/database/dao/ImageDaoMysql.php";

class ImageSearchBox extends Panel {

    private static string $SEARCH_QUERY_KEY = "s_term";
    private static string $SEARCH_LABEL_KEY = "s_label";
    private string $_back_click_id;
    private string $_backfill_id;
    private string $_objects_to_search;
    private string $_popup_type;
    private ImageDao $_image_dao;

    public function __construct(TemplateEngine $templateEngine, string $backClickId, string $backfillId, string $objectsToSearch, string $popupType) {
        parent::__construct($templateEngine, 'Zoeken', 'popup_search_fieldset');
        $this->_back_click_id = $backClickId;
        $this->_backfill_id = $backfillId;
        $this->_objects_to_search = $objectsToSearch;
        $this->_image_dao = ImageDaoMysql::getInstance();
        $this->_popup_type = $popupType;
    }

    public function getPanelContentTemplate(): string {
        return "system/image_search.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign("object", $this->_objects_to_search);
        $data->assign("backfill", $this->_backfill_id);
        $data->assign("back_click_id", $this->_back_click_id);

        $data->assign("search_field", $this->renderSearchField());
        $data->assign("search_button", $this->renderSearchButton());
        $data->assign("image_labels_field", $this->renderImageLabelsField());
        $data->assign("search_results", $this->renderSearchResults());
        $data->assign("no_results_message", $this->renderNoResultsMessage());
        $data->assign("popup_type", $this->_popup_type);
    }

    private function renderSearchField(): string {
        $search_query = $this->getCurrentSearchQuery();
        $search_field = new TextField(self::$SEARCH_QUERY_KEY, "Zoekterm", $search_query, false, false, "");
        return $search_field->render();
    }

    private function renderImageLabelsField(): string {
        $image_labels_values = array();
        $current_label_search = $this->getCurrentSearchLabel();
        $image_labels = $this->_image_dao->getAllLabels();
        foreach ($image_labels as $image_label) {
            $image_labels_values[] = array("name" => $image_label->getName(), "value" => $image_label->getId());
        }
        $image_labels_field = new Pulldown(self::$SEARCH_LABEL_KEY, "Label", strval($current_label_search), $image_labels_values, false, null, true);
        return $image_labels_field->render();
    }

    private function renderSearchResults(): array {
        $search_results_value = array();
        $search_results = $this->_image_dao->searchImages($this->getCurrentSearchQuery(), null, $this->getCurrentSearchLabel());
        if (count($search_results) > 0) {
            foreach ($search_results as $search_result) {
                $search_result_value = array();
                $search_result_value["id"] = $search_result->getId();
                $search_result_value["title"] = $search_result->getTitle();
                $search_result_value["published"] = $search_result->isPublished();
                $search_results_value[] = $search_result_value;
            }
        }
        return $search_results_value;
    }

    private function renderNoResultsMessage(): string {
        $information_message = new InformationMessage("Geen resultaten gevonden");
        return $information_message->render();
    }

    private function getCurrentSearchQuery(): ?string {
        $search_title = null;
        if (isset($_GET[self::$SEARCH_QUERY_KEY])) {
            $search_title = $_GET[self::$SEARCH_QUERY_KEY];
        }
        return $search_title;
    }

    private function getCurrentSearchLabel(): ?string {
        $current_search_label = null;
        if (isset($_GET[self::$SEARCH_LABEL_KEY]) && !empty($_GET[self::$SEARCH_LABEL_KEY])) {
            $current_search_label = intval($_GET[self::$SEARCH_LABEL_KEY]);
        }
        return $current_search_label;
    }

    private function renderSearchButton(): string {
        $search_button = new Button("", "Zoeken", "document.getElementById('search_form').submit(); return false;");
        return $search_button->render();
    }

}

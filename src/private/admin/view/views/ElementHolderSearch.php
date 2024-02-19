<?php
require_once CMS_ROOT . "/view/views/Search.php";
require_once CMS_ROOT . "/view/views/InformationMessage.php";
require_once CMS_ROOT . "/database/dao/ArticleDaoMysql.php";
require_once CMS_ROOT . "/database/dao/PageDaoMysql.php";
require_once CMS_ROOT . "/modules/pages/model/Page.php";

class ElementHolderSearch extends Panel {

    private static string $OBJECT_TYPE_KEY = "s_element_holder";
    private static string $SEARCH_QUERY_KEY = "s_term";
    private string $objectsToSearch;
    private string $backClickId;
    private string $backfillId;
    private ArticleDao $articleDao;
    private PageDao $pageDao;
    private string $popupType;

    public function __construct(TemplateEngine $templateEngine, string $backClickId, string $backfillId, string $objectsToSearch, string $popupType) {
        parent::__construct($templateEngine, 'Zoeken', 'popup_search_fieldset');
        $this->objectsToSearch = $objectsToSearch;
        $this->backClickId = $backClickId;
        $this->backfillId = $backfillId;
        $this->articleDao = ArticleDaoMysql::getInstance();
        $this->pageDao = PageDaoMysql::getInstance();
        $this->popupType = $popupType;
    }

    public function getPanelContentTemplate(): string {
        return "system/element_holder_search.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $data->assign("search_object", $this->objectsToSearch);
        $data->assign("backfill", $this->backfillId);
        $data->assign("back_click_id", $this->backClickId);

        $data->assign("search_field", $this->renderSearchField());
        $data->assign("search_options", $this->renderSearchOptionsField());
        $data->assign("search_button", $this->renderSearchButton());
        $data->assign("search_results", $this->renderSearchResults());
        $data->assign("no_results_message", $this->renderNoResultsMessage());
        $data->assign("popup_type", $this->popupType);
    }

    private function renderSearchField(): string {
        $search_query = $this->getCurrentSearchQuery();
        $search_field = new TextField(self::$SEARCH_QUERY_KEY, "Zoekterm", $search_query, false, false, "");
        return $search_field->render();
    }

    private function renderSearchResults(): array {
        $search_results_value = array();

        $search_query = $this->getCurrentSearchQuery();
        $search_results = null;
        if (isset($_GET[self::$OBJECT_TYPE_KEY])) {
            if ($_GET[self::$OBJECT_TYPE_KEY] == "element_holder_page") {
                $search_results = $this->pageDao->searchByTerm($search_query);
            } else if ($_GET[self::$OBJECT_TYPE_KEY] == "element_holder_article") {
                $search_results = $this->articleDao->searchArticles($search_query, null);
            }
        }
        if (!is_null($search_results) && count($search_results) > 0) {
            foreach ($search_results as $search_result) {
                $search_result_value = array();
                $search_result_value["id"] = $search_result->getId();
                $search_result_value["title"] = $search_result->getTitle();
                $search_results_value[] = $search_result_value;
            }
        }
        return $search_results_value;
    }

    private function renderSearchOptionsField(): string {
        $search_options = array();
        switch ($this->objectsToSearch) {
            case Search::$PAGES:
                $this->addPageOption($search_options);
                break;
            case Search::$ARTICLES:
                $this->addArticleOption($search_options);
                break;
            case Search::$ELEMENT_HOLDERS;
                $this->addPageOption($search_options);
                $this->addArticleOption($search_options);
                break;
        }
        $current_search_option = null;
        if (isset($_GET[self::$OBJECT_TYPE_KEY])) {
            $current_search_option = $_GET[self::$OBJECT_TYPE_KEY];
        }
        $search_options_field = new Pulldown(self::$OBJECT_TYPE_KEY, "Type", $current_search_option, $search_options, false, null);
        return $search_options_field->render();
    }

    private function renderNoResultsMessage(): string {
        $information_message = new InformationMessage("Geen resultaten gevonden");
        return $information_message->render();
    }

    private function getCurrentSearchQuery(): string {
        $search_title = "";
        if (isset($_GET[self::$SEARCH_QUERY_KEY])) {
            $search_title = $_GET[self::$SEARCH_QUERY_KEY];
        }
        return $search_title;
    }

    private function renderSearchButton(): string {
        $search_button = new Button("", "Zoeken", "document.getElementById('search_form').submit(); return false;");
        return $search_button->render();
    }

    private function addPageOption(array &$options): void {
        $options[] = array("name" => "Pagina", "value" => "element_holder_page");
    }

    private function addArticleOption(array &$options): void {
        $options[] = array("name" => "Artikel", "value" => "element_holder_article");
    }

}

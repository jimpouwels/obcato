<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/search.php";
    require_once CMS_ROOT . "view/views/information_message.php";
    require_once CMS_ROOT . "database/dao/article_dao.php";
    require_once CMS_ROOT . "database/dao/page_dao.php";
    require_once CMS_ROOT . "core/model/page.php";
    require_once CMS_ROOT . 'view/views/panel.php';

    class ElementHolderSearch extends Panel {

        private static string $OBJECT_TYPE_KEY = "s_element_holder";
        private static string $SEARCH_QUERY_KEY = "s_term";
        private static string $TEMPLATE = "system/element_holder_search.tpl";
        private string $_objects_to_search;
        private string $_back_click_id;
        private string $_backfill_id;
        private ArticleDao $_article_dao;
        private PageDao $_page_dao;

        public function __construct(string $back_click_id, string $backfill_id, string $objects_to_search) {
            parent::__construct('Zoeken', 'popup_search_fieldset');
            $this->_objects_to_search = $objects_to_search;
            $this->_back_click_id = $back_click_id;
            $this->_backfill_id = $backfill_id;
            $this->_article_dao = ArticleDao::getInstance();
            $this->_page_dao = PageDao::getInstance();
        }

        public function render(): string {
            return parent::render();
        }

        public function renderPanelContent(): string {
            $this->getTemplateEngine()->assign("search_object", $this->_objects_to_search);
            $this->getTemplateEngine()->assign("backfill", $this->_backfill_id);
            $this->getTemplateEngine()->assign("back_click_id", $this->_back_click_id);

            $this->getTemplateEngine()->assign("search_field", $this->renderSearchField());
            $this->getTemplateEngine()->assign("search_options", $this->renderSearchOptionsField());
            $this->getTemplateEngine()->assign("search_button", $this->renderSearchButton());
            $this->getTemplateEngine()->assign("search_results", $this->renderSearchResults());
            $this->getTemplateEngine()->assign("no_results_message", $this->renderNoResultsMessage());

            return $this->getTemplateEngine()->fetch(self::$TEMPLATE);
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
                    $search_results = $this->_page_dao->searchByTerm($search_query);
                } else if ($_GET[self::$OBJECT_TYPE_KEY] == "element_holder_article") {
                    $search_results = $this->_article_dao->searchArticles($search_query, null);
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
            switch ($this->_objects_to_search) {
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

?>

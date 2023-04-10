<?php
    defined('_ACCESS') or die;

    class ArticlesSearch extends Panel {

        private static $TEMPLATE = "articles/articles/search.tpl";

        private $_article_dao;
        private $_article_request_handler;

        public function __construct($article_request_handler) {
            parent::__construct($this->getTextResource('articles_search_box_title'), 'article_search');
            $this->_article_request_handler = $article_request_handler;
            $this->_article_dao = ArticleDao::getInstance();
        }

        public function render(): string {
            return parent::render();
        }

        public function renderPanelContent() {
            $this->getTemplateEngine()->assign("search_query_field", $this->renderSearchQueryField());
            $this->getTemplateEngine()->assign("term_query_field", $this->renderTermQueryField());
            $this->getTemplateEngine()->assign("search_button", $this->renderSearchButton());

            return $this->getTemplateEngine()->fetch("modules/" . self::$TEMPLATE);
        }

        private function renderSearchQueryField() {
            $default_search_value = $this->_article_request_handler->getSearchQuery();
            $search_query_field = new TextField("search_query", $this->getTextResource('articles_search_box_query'), $default_search_value, false, false, null);
            return $search_query_field->render();
        }

        private function renderTermQueryField() {
            $term_options = array();
            array_push($term_options, array('name' => $this->getTextResource('select_field_default_text'), 'value' => NULL));
            foreach ($this->_article_dao->getAllTerms() as $term) {
                array_push($term_options, array('name' => $term->getName(), 'value' => $term->getId()));
            }
            $term = $this->_article_request_handler->getSearchTerm();
            $term_query_field = new Pulldown("s_term", $this->getTextResource('articles_search_box_term'), $term, $term_options, false, "");
            return $term_query_field->render();
        }

        private function renderSearchButton() {
            $search_button = new Button("", $this->getTextResource('articles_search_box_search_button'), "document.getElementById('article_search').submit(); return false;");
            return $search_button->render();
        }

    }

?>

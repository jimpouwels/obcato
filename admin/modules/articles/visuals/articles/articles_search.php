<?php
    defined('_ACCESS') or die;

    class ArticlesSearch extends Panel {

        private ArticleDao $_article_dao;
        private ArticleRequestHandler $_article_request_handler;

        public function __construct(ArticleRequestHandler $article_request_handler) {
            parent::__construct($this->getTextResource('articles_search_box_title'), 'article_search');
            $this->_article_request_handler = $article_request_handler;
            $this->_article_dao = ArticleDao::getInstance();
        }

        public function getPanelContentTemplate(): string {
            return "modules/articles/articles/search.tpl";
        }

        public function loadPanelContent(Smarty_Internal_Data $data): void {
            $data->assign("search_query_field", $this->renderSearchQueryField());
            $data->assign("term_query_field", $this->renderTermQueryField());
            $data->assign("search_button", $this->renderSearchButton());
        }

        private function renderSearchQueryField(): string {
            $default_search_value = $this->_article_request_handler->getSearchQuery();
            $search_query_field = new TextField("search_query", $this->getTextResource('articles_search_box_query'), $default_search_value, false, false, null);
            return $search_query_field->render();
        }

        private function renderTermQueryField(): string {
            $term_options = array();
            foreach ($this->_article_dao->getAllTerms() as $term) {
                array_push($term_options, array('name' => $term->getName(), 'value' => $term->getId()));
            }
            $term = $this->_article_request_handler->getSearchTerm();
            $term_query_field = new Pulldown("s_term", $this->getTextResource('articles_search_box_term'), $term, $term_options, false, "", true);
            return $term_query_field->render();
        }

        private function renderSearchButton(): string {
            $search_button = new Button("", $this->getTextResource('articles_search_box_search_button'), "document.getElementById('article_search').submit(); return false;");
            return $search_button->render();
        }

    }

?>

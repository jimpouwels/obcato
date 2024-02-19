<?php

class ArticlesSearch extends Panel {

    private ArticleDao $articleDao;
    private ArticleRequestHandler $articleRequestHandler;

    public function __construct(TemplateEngine $templateEngine, ArticleRequestHandler $articleRequestHandler) {
        parent::__construct($templateEngine, $this->getTextResource('articles_search_box_title'), 'article_search');
        $this->articleRequestHandler = $articleRequestHandler;
        $this->articleDao = ArticleDaoMysql::getInstance();
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
        $defaultSearchValue = $this->articleRequestHandler->getSearchQuery();
        $searchQueryField = new TextField($this->getTemplateEngine(), "search_query", $this->getTextResource('articles_search_box_query'), $defaultSearchValue, false, false, null);
        return $searchQueryField->render();
    }

    private function renderTermQueryField(): string {
        $termOptions = array();
        foreach ($this->articleDao->getAllTerms() as $term) {
            $termOptions[] = array('name' => $term->getName(), 'value' => $term->getId());
        }
        $term = $this->articleRequestHandler->getSearchTerm();
        $termQueryField = new Pulldown($this->getTemplateEngine(), "s_term", $this->getTextResource('articles_search_box_term'), $term, $termOptions, false, "", true);
        return $termQueryField->render();
    }

    private function renderSearchButton(): string {
        $searchButton = new Button($this->getTemplateEngine(), "", $this->getTextResource('articles_search_box_search_button'), "document.getElementById('article_search').submit(); return false;");
        return $searchButton->render();
    }

}

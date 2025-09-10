<?php

namespace Obcato\Core\modules\articles\visuals\articles;


use Obcato\Core\database\dao\ArticleDao;
use Obcato\Core\database\dao\ArticleDaoMysql;
use Obcato\Core\modules\articles\ArticleRequestHandler;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\Button;
use Obcato\Core\view\views\Panel;
use Obcato\Core\view\views\Pulldown;
use Obcato\Core\view\views\TextField;

class ArticlesSearch extends Panel {

    private ArticleDao $articleDao;
    private ArticleRequestHandler $articleRequestHandler;

    public function __construct(ArticleRequestHandler $articleRequestHandler) {
        parent::__construct($this->getTextResource('articles_search_box_title'), 'article_search');
        $this->articleRequestHandler = $articleRequestHandler;
        $this->articleDao = ArticleDaoMysql::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "articles/templates/articles/search.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign("search_query_field", $this->renderSearchQueryField());
        $data->assign("term_query_field", $this->renderTermQueryField());
        $data->assign("search_button", $this->renderSearchButton());
    }

    private function renderSearchQueryField(): string {
        $defaultSearchValue = $this->articleRequestHandler->getSearchQuery();
        $searchQueryField = new TextField("search_query", $this->getTextResource('articles_search_box_query'), $defaultSearchValue, false, false, null);
        return $searchQueryField->render();
    }

    private function renderTermQueryField(): string {
        $termOptions = array();
        foreach ($this->articleDao->getAllTerms() as $term) {
            $termOptions[] = array('name' => $term->getName(), 'value' => $term->getId());
        }
        $term = $this->articleRequestHandler->getSearchTerm();
        $termQueryField = new Pulldown("s_term", $this->getTextResource('articles_search_box_term'), $term, $termOptions, false, "", true);
        return $termQueryField->render();
    }

    private function renderSearchButton(): string {
        $searchButton = new Button("", $this->getTextResource('articles_search_box_search_button'), "document.getElementById('article_search').submit(); return false;");
        return $searchButton->render();
    }

}

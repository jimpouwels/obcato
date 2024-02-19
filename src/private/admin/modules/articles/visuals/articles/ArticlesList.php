<?php
require_once CMS_ROOT . "/view/views/InformationMessage.php";
require_once CMS_ROOT . "/database/dao/AuthorizationDaoMysql.php";

class ArticlesList extends Panel {

    private ArticleDao $articleDao;
    private AuthorizationDao $authorizationDao;
    private ArticleRequestHandler $articleRequestHandler;

    public function __construct(TemplateEngine $templateEngine, ArticleRequestHandler $articleRequestHandler) {
        parent::__construct($templateEngine, $this->getTextResource('articles_search_results_title'), 'article_list');
        $this->articleRequestHandler = $articleRequestHandler;
        $this->articleDao = ArticleDaoMysql::getInstance();
        $this->authorizationDao = AuthorizationDaoMysql::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "modules/articles/articles/list.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign("search_results", $this->renderSearchResults());
        $data->assign("search_query", $this->articleRequestHandler->getSearchQuery());
        $data->assign("search_term", $this->getSearchTermName());
        $data->assign("no_results_message", $this->renderNoResultsMessage());
    }

    private function renderSearchResults(): array {
        $searchResults = array();

        $articles = $this->getSearchResults();
        foreach ($articles as $article) {
            $searchResult = array();
            $searchResult["id"] = $article->getId();
            $searchResult["title"] = $article->getTitle();

            $user = $this->authorizationDao->getUserById($article->getCreatedById());
            $searchResult["created_by"] = $user == null ? "" : $user->getUsername();
            $searchResult["created_at"] = $article->getCreatedAt();
            $searchResult["published"] = $article->isPublished();

            $searchResults[] = $searchResult;
        }
        return $searchResults;
    }

    private function getSearchResults(): array {
        if ($this->articleRequestHandler->isSearchAction()) {
            return $this->articleDao->searchArticles($this->articleRequestHandler->getSearchQuery(), $this->getSearchTermId());
        } else {
            return $this->articleDao->getAllArticles();
        }
    }

    private function getSearchTermId(): ?string {
        $searchTerm = $this->getSearchTerm();
        return $searchTerm?->getId();
    }

    private function getSearchTermName(): ?string {
        $search_term = $this->getSearchTerm();
        return $search_term?->getName();
    }

    private function getSearchTerm(): ?ArticleTerm {
        $search_term_name = $this->articleRequestHandler->getSearchTerm();
        if ($search_term_name) {
            return $this->articleDao->getTerm($search_term_name);
        }
        return null;
    }

    private function renderNoResultsMessage(): string {
        $message = new InformationMessage($this->getTemplateEngine(), $this->getTextResource("articles_list_message_no_articles_found"));
        return $message->render();
    }

}

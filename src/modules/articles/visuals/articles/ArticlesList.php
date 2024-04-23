<?php

namespace Obcato\Core\modules\articles\visuals\articles;

use Obcato\Core\database\dao\AuthorizationDao;
use Obcato\Core\database\dao\AuthorizationDaoMysql;
use Obcato\Core\modules\articles\ArticleRequestHandler;
use Obcato\Core\modules\articles\model\ArticleTerm;
use Obcato\Core\modules\articles\service\ArticleInteractor;
use Obcato\Core\modules\articles\service\ArticleService;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\InformationMessage;
use Obcato\Core\view\views\Panel;

class ArticlesList extends Panel {

    private ArticleService $articleService;
    private AuthorizationDao $authorizationDao;
    private ArticleRequestHandler $articleRequestHandler;

    public function __construct(ArticleRequestHandler $articleRequestHandler) {
        parent::__construct($this->getTextResource('articles_search_results_title'), 'article_list');
        $this->articleRequestHandler = $articleRequestHandler;
        $this->articleService = ArticleInteractor::getInstance();
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
            return $this->articleService->searchArticles($this->articleRequestHandler->getSearchQuery(), $this->getSearchTermId());
        } else {
            return $this->articleService->getAllArticles();
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
            return $this->articleService->getTerm($search_term_name);
        }
        return null;
    }

    private function renderNoResultsMessage(): string {
        $message = new InformationMessage($this->getTextResource("articles_list_message_no_articles_found"));
        return $message->render();
    }

}

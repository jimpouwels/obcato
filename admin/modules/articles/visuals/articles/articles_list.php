<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/information_message.php";

    class ArticlesList extends Panel {

        private static string $TEMPLATE = "articles/articles/list.tpl";

        private ArticleDao $_article_dao;
        private ArticleRequestHandler $_article_request_handler;

        public function __construct(ArticleRequestHandler $article_request_handler) {
            parent::__construct($this->getTextResource('articles_search_results_title'), 'article_list');
            $this->_article_request_handler = $article_request_handler;
            $this->_article_dao = ArticleDao::getInstance();
        }

        public function render(): string {
            return parent::render();
        }

        public function renderPanelContent(): string {
            $this->getTemplateEngine()->assign("search_results", $this->renderSearchResults());
            $this->getTemplateEngine()->assign("search_query", $this->_article_request_handler->getSearchQuery());
            $this->getTemplateEngine()->assign("search_term", $this->getSearchTermName());
            $this->getTemplateEngine()->assign("no_results_message", $this->renderNoResultsMessage());
            return $this->getTemplateEngine()->fetch("modules/" . self::$TEMPLATE);
        }

        private function renderSearchResults(): array {
            $search_results = array();

            $articles = $this->getSearchResults();
            foreach($articles as $article) {
                $search_result = array();
                $search_result["id"] = $article->getId();
                $search_result["title"] = $article->getTitle();

                $user = $article->getCreatedBy();
                $search_result["created_by"] = $user ? null : $user->getUsername();
                $search_result["created_at"] = $article->getCreatedAt();
                $search_result["published"] = $article->isPublished();

                $search_results[] = $search_result;
            }
            return $search_results;
        }

        private function getSearchResults(): array {
            if ($this->_article_request_handler->isSearchAction()) {
                return $this->_article_dao->searchArticles($this->_article_request_handler->getSearchQuery(),  $this->getSearchTermId());
            } else {
                return $this->_article_dao->getAllArticles();
            }
        }

        private function getSearchTermId(): ?string {
            $search_term = $this->getSearchTerm();
            return $search_term ? $search_term->getId() : null;
        }

        private function getSearchTermName(): ?string {
            $search_term = $this->getSearchTerm();
            return $search_term ? $search_term->getName() : null;
        }

        private function getSearchTerm(): ?ArticleTerm {
            $search_term_name = $this->_article_request_handler->getSearchTerm();
            if ($search_term_name) {
                return $this->_article_dao->getTerm($search_term_name);
            }
            return null;
        }

        private function renderNoResultsMessage(): string {
            $message = new InformationMessage("Geen artikelen gevonden.");
            return $message->render();
        }

    }

?>

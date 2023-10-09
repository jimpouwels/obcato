<?php
require_once CMS_ROOT . "/request_handlers/ElementHolderRequestHandler.php";
require_once CMS_ROOT . "/database/dao/ArticleDaoMysql.php";
require_once CMS_ROOT . "/database/dao/ElementDaoMysql.php";
require_once CMS_ROOT . "/modules/articles/ArticleForm.php";
require_once CMS_ROOT . '/friendly_urls/FriendlyUrlManager.php';

class ArticleRequestHandler extends ElementHolderRequestHandler {

    private static string $ARTICLE_ID_POST = "element_holder_id";
    private static string $ARTICLE_ID_GET = "article";
    private ?Article $currentArticle = null;
    private ArticleDao $_article_dao;
    private FriendlyUrlManager $_friendly_url_manager;

    public function __construct() {
        parent::__construct();
        $this->_article_dao = ArticleDaoMysql::getInstance();
        $this->_friendly_url_manager = FriendlyUrlManager::getInstance();
    }

    public function handleGet(): void {
        $this->currentArticle = $this->getArticleFromGetRequest();
    }

    public function handlePost(): void {
        try {
            parent::handlePost();
            if ($this->isAddArticleAction()) {
                $this->addArticle();
            } else if ($this->isUpdateArticleAction()) {
                $this->updateArticle();
            } else if ($this->isDeleteArticleAction()) {
                $this->deleteArticle();
            }
        } catch (ElementHolderContainsErrorsException) {
            $this->sendErrorMessage($this->getTextResource('page_not_saved_error_message'));
        }
    }

    public function loadElementHolderFromPostRequest(): ?ElementHolder {
        $this->currentArticle = $this->getArticleFromPostRequest();
        return $this->currentArticle;
    }


    public function getCurrentArticle(): ?Article {
        return $this->currentArticle;
    }

    public function getSearchQuery(): string {
        if (isset($_GET['search_query'])) {
            return $_GET['search_query'];
        }
        return "";
    }

    public function getSearchTerm(): ?string {
        if (isset($_GET['s_term'])) {
            return $_GET['s_term'];
        }
        return "";
    }

    public function isSearchAction(): bool {
        return isset($_GET['action']) && $_GET['action'] == 'search';
    }

    private function updateArticle(): void {
        try {
            $article_form = new ArticleForm($this->currentArticle);
            $article_form->loadFields();
            $this->_article_dao->updateArticle($this->currentArticle);
            $this->updateSelectedTerms($article_form->getSelectedTerms());
            $this->deleteSelectedTerms($article_form);
            $this->_friendly_url_manager->insertOrUpdateFriendlyUrlForArticle($this->currentArticle);
            foreach ($this->_article_dao->getAllChildArticles($this->currentArticle->getId()) as $child_article) {
                $this->_friendly_url_manager->insertOrUpdateFriendlyUrlForArticle($child_article);
            }
            $this->sendSuccessMessage("Artikel succesvol opgeslagen");
        } catch (ElementHolderContainsErrorsException $e) {
            $this->sendErrorMessage("Artikel niet opgeslagen, verwerk de fouten");
        } catch (FormException $e) {
            $this->sendErrorMessage("Artikel niet opgeslagen, verwerk de fouten");
        }
    }

    private function addArticle(): void {
        $new_article = $this->_article_dao->createArticle();
        $this->sendSuccessMessage("Artikel succesvol aangemaakt");
        $this->redirectTo($this->getBackendBaseUrl() . '&article=' . $new_article->getId());
    }

    private function deleteArticle(): void {
        $this->_article_dao->deleteArticle($this->currentArticle);
        $this->sendSuccessMessage("Artikel succesvol verwijderd");
        $this->redirectTo($this->getBackendBaseUrl());
    }

    private function updateSelectedTerms(array $selected_terms): void {
        if (count($selected_terms) == 0) return;
        $existing_terms = $this->_article_dao->getTermsForArticle($this->currentArticle->getId());
        foreach ($selected_terms as $selected_term_id) {
            if (!$this->termAlreadyExists($selected_term_id, $existing_terms)) {
                $this->_article_dao->addTermToArticle($selected_term_id, $this->currentArticle);
            }
        }
    }

    private function deleteSelectedTerms(ArticleForm $article_form): void {
        foreach ($article_form->getTermsToDelete() as $term_to_delete) {
            $this->_article_dao->deleteTermFromArticle($term_to_delete->getId(), $this->currentArticle);
        }
    }

    private function getArticleFromGetRequest(): ?Article {
        if (isset($_GET[self::$ARTICLE_ID_GET])) {
            return $this->getArticleFromDatabase($_GET[self::$ARTICLE_ID_GET]);
        }
        return null;
    }

    private function getArticleFromPostRequest(): ?Article {
        if (isset($_POST[self::$ARTICLE_ID_POST])) {
            return $this->getArticleFromDatabase($_POST[self::$ARTICLE_ID_POST]);
        }
        return null;
    }

    private function getArticleFromDatabase(int $article_id): Article {
        return $this->_article_dao->getArticle($article_id);
    }

    private function termAlreadyExists(int $term_id, array $existing_terms): bool {
        foreach ($existing_terms as $existing_term) {
            if ($existing_term->getId() == $term_id) {
                return true;
            }
        }
        return false;
    }

    private function isUpdateArticleAction(): bool {
        return isset($_POST["action"]) && $_POST["action"] == "update_element_holder";
    }

    private function isDeleteArticleAction(): bool {
        return isset($_POST["action"]) && $_POST["action"] == "delete_article";
    }

    private function isAddArticleAction(): bool {
        return isset($_POST["add_article_action"]);
    }

}

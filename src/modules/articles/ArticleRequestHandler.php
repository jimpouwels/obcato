<?php

namespace Obcato\Core\modules\articles;

use Obcato\Core\core\form\FormException;
use Obcato\Core\core\model\ElementHolder;
use Obcato\Core\friendly_urls\FriendlyUrlManager;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\articles\service\ArticleInteractor;
use Obcato\Core\modules\articles\service\ArticleService;
use Obcato\Core\request_handlers\ElementHolderRequestHandler;
use Obcato\Core\request_handlers\exceptions\ElementHolderContainsErrorsException;

class ArticleRequestHandler extends ElementHolderRequestHandler {

    private static string $ARTICLE_ID_POST = "element_holder_id";
    private static string $ARTICLE_ID_GET = "article";
    private ?Article $currentArticle = null;
    private ArticleService $articleService;
    private FriendlyUrlManager $friendlyUrlManager;

    public function __construct() {
        parent::__construct();
        $this->articleService = ArticleInteractor::getInstance();
        $this->friendlyUrlManager = FriendlyUrlManager::getInstance();
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
            $this->sendErrorMessage($this->getTextResource('article_not_saved_error_message'));
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
            $articleForm = new ArticleForm($this->currentArticle);
            $articleForm->loadFields();
            $this->articleService->updateArticle($this->currentArticle);
            $this->updateSelectedTerms($articleForm->getSelectedTerm());
            $this->deleteSelectedTerms($articleForm);
            $this->friendlyUrlManager->insertOrUpdateFriendlyUrlForArticle($this->currentArticle);
            foreach ($this->articleService->getAllChildArticlesFor($this->currentArticle) as $child_article) {
                $this->friendlyUrlManager->insertOrUpdateFriendlyUrlForArticle($child_article);
            }
            $this->sendSuccessMessage($this->getTextResource('article_saved_message'));
        } catch (ElementHolderContainsErrorsException|FormException) {
            $this->sendErrorMessage($this->getTextResource('article_not_saved_error_message'));
        }
    }

    private function addArticle(): void {
        $newArticle = $this->articleService->createArticle();
        $this->sendSuccessMessage($this->getTextResource('article_created_message'));
        $this->redirectTo($this->getBackendBaseUrl() . '&article=' . $newArticle->getId());
    }

    private function deleteArticle(): void {
        $this->articleService->deleteArticle($this->currentArticle);
        $this->sendSuccessMessage($this->getTextResource('article_deleted_message'));
        $this->redirectTo($this->getBackendBaseUrl());
    }

    private function updateSelectedTerms(array $selectedTerms): void {
        if (count($selectedTerms) == 0) return;
        $existingTerms = $this->articleService->getTermsForArticle($this->currentArticle);
        foreach ($selectedTerms as $selectedTermId) {
            if (!$this->termAlreadyExists($selectedTermId, $existingTerms)) {
                $this->articleService->addTermToArticle($selectedTermId, $this->currentArticle);
            }
        }
    }

    private function deleteSelectedTerms(ArticleForm $articleForm): void {
        foreach ($articleForm->getTermsToDelete() as $termToDelete) {
            $this->articleService->deleteTermFromArticle($termToDelete->getId(), $this->currentArticle);
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
        return $this->articleService->getArticle($article_id);
    }

    private function termAlreadyExists(int $termId, array $existingTerms): bool {
        foreach ($existingTerms as $existingTerm) {
            if ($existingTerm->getId() == $termId) {
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

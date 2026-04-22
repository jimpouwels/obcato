<?php

namespace Pageflow\Core\modules\articles;

use Pageflow\Core\core\form\FormException;
use Pageflow\Core\database\dao\ArticleDao;
use Pageflow\Core\database\dao\ArticleDaoMysql;
use Pageflow\Core\modules\articles\model\ArticleTerm;
use Pageflow\Core\request_handlers\HttpRequestHandler;

class TermRequestHandler extends HttpRequestHandler {

    private ?ArticleTerm $currentTerm = null;
    private ArticleDao $articleDao;

    public function __construct() {
        $this->articleDao = ArticleDaoMysql::getInstance();
    }

    public function handleGet(): void {
        $this->currentTerm = $this->getTermFromGetRequest();
    }

    public function handlePost(): void {
        $this->currentTerm = $this->getTermFromPostRequest();
        if ($this->isAddTermAction()) {
            $this->addTerm();
        } else if ($this->isUpdateTermAction()) {
            $this->updateTerm();
        } else if ($this->isDeleteTermAction()) {
            $this->deleteTerm();
        }
    }

    public function getCurrentTerm(): ?ArticleTerm {
        return $this->currentTerm;
    }

    private function addTerm(): void {
        $newTerm = $this->articleDao->createTerm($this->getTextResource("articles_terms_default_term_name"));
        $this->sendSuccessMessage($this->getTextResource("articles_terms_term_create_success_message"));
        $this->redirectTo($this->getBackendBaseUrl() . "&term=" . $newTerm->getId());
    }

    private function updateTerm(): void {
        $termForm = new TermForm($this->currentTerm);
        try {
            $termForm->loadFields();
            $this->articleDao->updateTerm($this->currentTerm);
            $this->sendSuccessMessage($this->getTextResource("articles_terms_term_save_success_message"));
        } catch (FormException $e) {
            $this->sendErrorMessage($this->getTextResource("articles_terms_term_save_error_message"));
        }
    }

    private function deleteTerm(): void {
        if ($this->currentTerm) {
            $this->articleDao->deleteTerm($this->currentTerm);
            $this->sendSuccessMessage($this->getTextResource("articles_terms_term_delete_success_message"));
            $this->redirectTo($this->getBackendBaseUrl());
        }
    }

    private function getTermFromGetRequest(): ?ArticleTerm {
        if (isset($_GET["term"])) {
            return $this->getTerm((int)$_GET["term"]);
        }
        return null;
    }

    private function getTermFromPostRequest(): ?ArticleTerm {
        if (isset($_POST["term_id"]) && $_POST["term_id"] != "") {
            return $this->getTerm((int)$_POST["term_id"]);
        }
        return null;
    }

    private function getTerm(int $termId): ArticleTerm {
        return $this->articleDao->getTerm($termId);
    }

    private function isUpdateTermAction(): bool {
        return isset($_POST["action"]) && $_POST["action"] == "update_term";
    }

    private function isAddTermAction(): bool {
        return isset($_POST["add_term_action"]) && $_POST["add_term_action"] == "add_term";
    }

    private function isDeleteTermAction(): bool {
        return isset($_POST["action"]) && $_POST["action"] == "delete_term";
    }
}
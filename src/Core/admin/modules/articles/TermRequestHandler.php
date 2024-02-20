<?php

namespace Obcato\Core;

use Obcato\Core\admin\core\form\FormException;

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
        } else if ($this->isDeleteTermsAction()) {
            $this->deleteTerms();
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

    private function deleteTerms(): void {
        $terms = $this->articleDao->getAllTerms();
        foreach ($terms as $term) {
            if (isset($_POST["term_" . $term->getId() . "_delete"])) {
                $this->articleDao->deleteTerm($term);
            }
        }
        $this->sendSuccessMessage($this->getTextResource("articles_terms_term_delete_success_message"));
    }

    private function getTermFromGetRequest(): ?ArticleTerm {
        if (isset($_GET["term"])) {
            return $this->getTerm($_GET["term"]);
        }
        return null;
    }

    private function getTermFromPostRequest(): ?ArticleTerm {
        if (isset($_POST["term_id"]) && $_POST["term_id"] != "") {
            return $this->getTerm($_POST["term_id"]);
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

    private function isDeleteTermsAction(): bool {
        return isset($_POST["term_delete_action"]) && $_POST["term_delete_action"] == "delete_terms";
    }
}
<?php
require_once CMS_ROOT . "/core/form/Form.php";
require_once CMS_ROOT . "/database/dao/ArticleDaoMysql.php";

class TermForm extends Form {

    private ArticleTerm $term;
    private ArticleDao $articleDao;

    public function __construct($term) {
        $this->term = $term;
        $this->articleDao = ArticleDaoMysql::getInstance();
    }

    public function loadFields(): void {
        $this->term->setName($this->getMandatoryFieldValue("name"));
        if ($this->hasErrors() || $this->termExists()) {
            throw new FormException();
        }
    }

    private function termExists(): bool {
        $existingTerm = $this->articleDao->getTermByName($this->term->getName());
        if ($this->term->getId() != $existingTerm?->getId()) {
            $this->raiseError("name", $this->getTextResource("terms_error_message_duplicate_term_name"));
            return true;
        }
        return false;
    }

}
    
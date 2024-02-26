<?php

namespace Obcato\Core\modules\articles;

use Obcato\Core\core\form\Form;
use Obcato\Core\core\form\FormException;
use Obcato\Core\database\dao\ArticleDao;
use Obcato\Core\database\dao\ArticleDaoMysql;
use Obcato\Core\modules\articles\model\ArticleTerm;

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
        if ($existingTerm && $this->term->getId() != $existingTerm->getId()) {
            $this->raiseError("name", $this->getTextResource("terms_error_message_duplicate_term_name"));
            return true;
        }
        return false;
    }

}

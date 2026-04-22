<?php

namespace Pageflow\Core\modules\articles;

use Pageflow\Core\core\form\Form;
use Pageflow\Core\core\form\FormException;
use Pageflow\Core\database\dao\ArticleDao;
use Pageflow\Core\database\dao\ArticleDaoMysql;
use Pageflow\Core\modules\articles\model\ArticleTerm;

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

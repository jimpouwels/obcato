<?php
require_once CMS_ROOT . "/core/form/Form.php";
require_once CMS_ROOT . "/database/dao/ArticleDaoMysql.php";

class TermForm extends Form {

    private ArticleTerm $_term;
    private ArticleDao $_article_dao;

    public function __construct($term) {
        $this->_term = $term;
        $this->_article_dao = ArticleDaoMysql::getInstance();
    }

    public function loadFields(): void {
        $this->_term->setName($this->getMandatoryFieldValue("name"));
        if ($this->hasErrors() || $this->termExists()) {
            throw new FormException();
        }
    }

    private function termExists(): bool {
        $existing_term = $this->_article_dao->getTermByName($this->_term->getName());
        if (!is_null($existing_term) && $this->_term->getId() != $existing_term->getId()) {
            $this->raiseError("name", "Er bestaat al een term met deze naam");
            return true;
        }
        return false;
    }

}
    
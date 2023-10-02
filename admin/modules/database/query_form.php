<?php

defined('_ACCESS') or die;

require_once CMS_ROOT . "/core/form/Form.php";

class QueryForm extends Form {

    private ?string $_query = null;

    public function loadFields(): void {
        $this->_query = $this->getMandatoryFieldValue('query', 'U heeft geen query ingevoerd');
        if ($this->hasErrors()) {
            throw new FormException();
        }
    }

    public function getQuery(): ?string {
        return $this->_query;
    }
}
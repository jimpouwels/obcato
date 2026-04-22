<?php

namespace Pageflow\Core\modules\database;

use Pageflow\Core\core\form\Form;
use Pageflow\Core\core\form\FormException;

class QueryForm extends Form {

    private ?string $query = null;

    public function loadFields(): void {
        $this->query = $this->getMandatoryFieldValue('query');
        if ($this->hasErrors()) {
            throw new FormException();
        }
    }

    public function getQuery(): ?string {
        return $this->query;
    }
}
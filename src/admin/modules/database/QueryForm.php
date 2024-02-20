<?php

namespace Obcato\Core\admin\modules\database;

use Obcato\Core\admin\core\form\Form;
use Obcato\Core\admin\core\form\FormException;

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
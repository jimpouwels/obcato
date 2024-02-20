<?php

namespace Obcato\Core\admin\elements\table_of_contents_element;

use Obcato\Core\admin\core\form\FormException;
use Obcato\Core\admin\request_handlers\ElementForm;

class TableOfContentsElementForm extends ElementForm {

    private TableOfContentsElement $tableOfContentsElement;

    public function __construct(TableOfContentsElement $tableOfContentsElement) {
        parent::__construct($tableOfContentsElement);
        $this->tableOfContentsElement = $tableOfContentsElement;
    }

    public function loadFields(): void {
        $elementId = $this->tableOfContentsElement->getId();
        $title = $this->getFieldValue('element_' . $elementId . '_title');
        if ($this->hasErrors()) {
            throw new FormException();
        } else {
            parent::loadFields();
            $this->tableOfContentsElement->setTitle($title);
        }
    }
}
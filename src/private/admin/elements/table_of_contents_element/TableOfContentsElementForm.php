<?php
require_once CMS_ROOT . "/request_handlers/ElementForm.php";
require_once CMS_ROOT . "/utilities/DateUtility.php";

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
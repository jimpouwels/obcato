<?php
require_once CMS_ROOT . "/request_handlers/ElementForm.php";
require_once CMS_ROOT . "/utilities/DateUtility.php";

class TableOfContentsElementForm extends ElementForm {

    private TableOfContentsElement $_table_of_contents_element;

    public function __construct(TableOfContentsElement $table_of_contents_element) {
        parent::__construct($table_of_contents_element);
        $this->_table_of_contents_element = $table_of_contents_element;
    }

    public function loadFields(): void {
        $element_id = $this->_table_of_contents_element->getId();
        $title = $this->getFieldValue('element_' . $element_id . '_title');
        if ($this->hasErrors()) {
            throw new FormException();
        } else {
            parent::loadFields();
            $this->_table_of_contents_element->setTitle($title);
        }
    }
}
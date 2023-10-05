<?php
require_once CMS_ROOT . "/view/views/ElementVisual.php";
require_once CMS_ROOT . "/view/views/TextField.php";
require_once CMS_ROOT . "/view/views/DateField.php";
require_once CMS_ROOT . "/view/views/TermSelector.php";

class TableOfContentsElementEditor extends ElementVisual {

    private static string $TEMPLATE = "elements/table_of_contents_element/table_of_contents_element_form.tpl";
    private TableOfContentsElement $_element;

    public function __construct(TableOfContentsElement $element) {
        parent::__construct();
        $this->_element = $element;
    }

    public function getElement(): Element {
        return $this->_element;
    }

    public function renderElementForm(Smarty_Internal_Data $data): string {
        $title_field = new TextField("element_" . $this->_element->getId() . "_title", "Titel", $this->_element->getTitle(), false, true, null);
        $data->assign("title_field", $title_field->render());
        return $this->getTemplateEngine()->fetch(self::$TEMPLATE, $data);
    }

}

?>
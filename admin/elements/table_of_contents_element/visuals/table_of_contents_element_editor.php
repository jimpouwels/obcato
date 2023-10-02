<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/view/views/element_visual.php";
require_once CMS_ROOT . "/view/views/form_textfield.php";
require_once CMS_ROOT . "/view/views/form_date.php";
require_once CMS_ROOT . "/view/views/term_selector.php";

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
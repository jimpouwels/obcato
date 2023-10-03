<?php


defined('_ACCESS') or die;

require_once CMS_ROOT . "/view/views/ElementVisual.php";
require_once CMS_ROOT . "/view/views/TextField.php";
require_once CMS_ROOT . "/view/views/TextArea.php";

class TextElementEditorVisual extends ElementVisual {

    private static string $TEMPLATE = "elements/text_element/text_element_form.tpl";

    private TextElement $_text_element;

    public function __construct(TextElement $text_element) {
        parent::__construct();
        $this->_text_element = $text_element;
    }

    public function getElement(): Element {
        return $this->_text_element;
    }

    public function renderElementForm(Smarty_Internal_Data $data): string {
        $title_field = new TextField('element_' . $this->_text_element->getId() . '_title', $this->getTextResource("text_element_editor_title"), $this->_text_element->getTitle(), false, true, null);
        $text_field = new TextArea('element_' . $this->_text_element->getId() . '_text', $this->getTextResource("text_element_editor_text"), $this->_text_element->getText(), false, true, null);

        $data->assign("title_field", $title_field->render());
        $data->assign("text_field", $text_field->render());
        return $this->getTemplateEngine()->fetch(self::$TEMPLATE, $data);
    }

}

?>
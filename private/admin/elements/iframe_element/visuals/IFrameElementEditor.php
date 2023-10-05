<?php
require_once CMS_ROOT . "/view/views/ElementVisual.php";
require_once CMS_ROOT . "/view/views/TextField.php";
require_once CMS_ROOT . "/view/views/ImagePicker.php";

class IFrameElementEditor extends ElementVisual {

    private static string $TEMPLATE = "elements/iframe_element/iframe_element_form.tpl";
    private IFrameElement $_iframe_element;

    public function __construct(IFrameElement $iframe_element) {
        parent::__construct();
        $this->_iframe_element = $iframe_element;
    }

    public function getElement(): Element {
        return $this->_iframe_element;
    }

    public function renderElementForm(Smarty_Internal_Data $data): string {
        $title_field = new TextField($this->createFieldId("title"), $this->getTextResource("iframe_element_editor_title"), htmlentities($this->_iframe_element->getTitle()), false, false, null);
        $url_field = new TextField($this->createFieldId("url"), $this->getTextResource("iframe_element_editor_url"), $this->_iframe_element->getUrl(), false, true, null);
        $width_field = new TextField($this->createFieldId("width"), $this->getTextResource("iframe_element_editor_width"), $this->_iframe_element->getWidth(), false, false, "size_field");
        $height_field = new TextField($this->createFieldId("height"), $this->getTextResource("iframe_element_editor_height"), $this->_iframe_element->getHeight(), false, false, "size_field");

        $data->assign("title_field", $title_field->render());
        $data->assign("url_field", $url_field->render());
        $data->assign("width_field", $width_field->render());
        $data->assign("height_field", $height_field->render());
        return $this->getTemplateEngine()->fetch(self::$TEMPLATE, $data);
    }

    private function createFieldId($property_name): string {
        return "element_" . $this->_iframe_element->getId() . "_" . $property_name;
    }

}

?>

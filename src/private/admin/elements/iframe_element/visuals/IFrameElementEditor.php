<?php
require_once CMS_ROOT . "/view/views/ElementVisual.php";
require_once CMS_ROOT . "/view/views/TextField.php";
require_once CMS_ROOT . "/view/views/ImagePicker.php";

class IFrameElementEditor extends ElementVisual {

    private static string $TEMPLATE = "elements/iframe_element/iframe_element_form.tpl";
    private IFrameElement $iframeElement;

    public function __construct(TemplateEngine $templateEngine, IFrameElement $iframeElement) {
        parent::__construct($templateEngine);
        $this->iframeElement = $iframeElement;
    }

    public function getElement(): Element {
        return $this->iframeElement;
    }

    public function getElementFormTemplateFilename(): string {
        return self::$TEMPLATE;
    }

    public function loadElementForm(TemplateData $data): void {
        $titleField = new TextField($this->getTemplateEngine(), $this->createFieldId("title"), $this->getTextResource("iframe_element_editor_title"), htmlentities($this->iframeElement->getTitle()), false, false, null);
        $urlField = new TextField($this->getTemplateEngine(), $this->createFieldId("url"), $this->getTextResource("iframe_element_editor_url"), $this->iframeElement->getUrl(), false, true, null);
        $widthField = new TextField($this->getTemplateEngine(), $this->createFieldId("width"), $this->getTextResource("iframe_element_editor_width"), $this->iframeElement->getWidth(), false, false, "size_field");
        $heightField = new TextField($this->getTemplateEngine(), $this->createFieldId("height"), $this->getTextResource("iframe_element_editor_height"), $this->iframeElement->getHeight(), false, false, "size_field");

        $data->assign("title_field", $titleField->render());
        $data->assign("url_field", $urlField->render());
        $data->assign("width_field", $widthField->render());
        $data->assign("height_field", $heightField->render());
    }

    private function createFieldId($propertyName): string {
        return "element_" . $this->iframeElement->getId() . "_" . $propertyName;
    }

}

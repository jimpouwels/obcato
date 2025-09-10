<?php

namespace Obcato\Core\elements\iframe_element\visuals;

use Obcato\Core\core\model\Element;
use Obcato\Core\elements\iframe_element\IFrameElement;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\ElementVisual;
use Obcato\Core\view\views\TextField;

class IFrameElementEditor extends ElementVisual {

    private static string $TEMPLATE = "iframe_element/templates/iframe_element_form.tpl";
    private IFrameElement $iframeElement;

    public function __construct(IFrameElement $iframeElement) {
        parent::__construct();
        $this->iframeElement = $iframeElement;
    }

    public function getElement(): Element {
        return $this->iframeElement;
    }

    public function getElementFormTemplateFilename(): string {
        return self::$TEMPLATE;
    }

    public function loadElementForm(TemplateData $data): void {
        $titleField = new TextField($this->createFieldId("title"), $this->getTextResource("iframe_element_editor_title"), htmlentities($this->iframeElement->getTitle()), false, false, null);
        $urlField = new TextField($this->createFieldId("url"), $this->getTextResource("iframe_element_editor_url"), $this->iframeElement->getUrl(), false, true, null);
        $widthField = new TextField($this->createFieldId("width"), $this->getTextResource("iframe_element_editor_width"), $this->iframeElement->getWidth(), false, false, "size_field");
        $heightField = new TextField($this->createFieldId("height"), $this->getTextResource("iframe_element_editor_height"), $this->iframeElement->getHeight(), false, false, "size_field");

        $data->assign("title_field", $titleField->render());
        $data->assign("url_field", $urlField->render());
        $data->assign("width_field", $widthField->render());
        $data->assign("height_field", $heightField->render());
    }

    public function includeLinkSelector(): bool
    {
        return false;
    }

    private function createFieldId($propertyName): string {
        return "element_" . $this->iframeElement->getId() . "_" . $propertyName;
    }

}

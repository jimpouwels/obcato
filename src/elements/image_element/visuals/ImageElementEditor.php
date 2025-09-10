<?php

namespace Obcato\Core\elements\image_element\visuals;

use Obcato\Core\core\model\Element;
use Obcato\Core\database\dao\LinkDaoMysql;
use Obcato\Core\elements\image_element\ImageElement;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\ElementVisual;
use Obcato\Core\view\views\ImagePicker;
use Obcato\Core\view\views\Pulldown;
use Obcato\Core\view\views\TextField;

class ImageElementEditor extends ElementVisual {

    private static string $TEMPLATE = "image_element/templates/image_element_form.tpl";
    private ImageElement $imageElement;

    public function __construct(ImageElement $element) {
        parent::__construct();
        $this->imageElement = $element;
        $this->linkDao = LinkDaoMysql::getInstance();
    }

    public function getElement(): Element {
        return $this->imageElement;
    }

    public function getElementFormTemplateFilename(): string {
        return self::$TEMPLATE;
    }

    public function loadElementForm(TemplateData $data): void {
        $titleField = new TextField($this->createFieldId("title"), $this->getTextResource("image_element_editor_title"), htmlentities($this->imageElement->getTitle()), false, false, null);
        $imagePicker = new ImagePicker("image_image_ref_" . $this->imageElement->getId(), $this->getTextResource("image_element_editor_image"), $this->imageElement->getImageId(), "update_element_holder");
        $urlField = new TextField($this->createFieldId("url"), $this->getTextResource("image_element_editor_url"), $this->imageElement->getUrl(), false, false, "url_field");
        $widthField = new TextField($this->createFieldId("width"), $this->getTextResource("image_element_editor_width"), $this->imageElement->getWidth(), false, false, "size_field");
        $heightField = new TextField($this->createFieldId("height"), $this->getTextResource("image_element_editor_height"), $this->imageElement->getHeight(), false, false, "size_field");
        $linkSelector = new Pulldown($this->createFieldId("link"), $this->getTextResource("image_element_editor_link"), $this->imageElement->getLinkId(), $this->getLinkOptions(), false, "", true);

        $data->assign("alignment_field", $this->renderAlignmentField());
        $data->assign("title_field", $titleField->render());
        $data->assign("width_field", $widthField->render());
        $data->assign("url_field", $urlField->render());
        $data->assign("height_field", $heightField->render());
        $data->assign("image_picker", $imagePicker->render());
        $data->assign("image_id", $this->imageElement->getImageId());
        $data->assign("selected_image_title", $this->getSelectedImageTitle());
        $data->assign("link_selector_field", $linkSelector->render());
    }

    public function includeLinkSelector(): bool
    {
        return false;
    }

    private function renderAlignmentField(): string {
        $alignmentOptions = array();
        $alignmentOptions[] = array("name" => $this->getTextResource("image_element_align_left"), "value" => "left");
        $alignmentOptions[] = array("name" => $this->getTextResource("image_element_align_right"), "value" => "right");
        $alignmentOptions[] = array("name" => $this->getTextResource("image_element_align_center"), "value" => "center");
        $currentAlignment = $this->imageElement->getAlign();
        $alignmentField = new Pulldown("element_" . $this->imageElement->getId() . "_align", $this->getTextResource("image_element_editor_alignment"), $currentAlignment, $alignmentOptions, false, null);
        return $alignmentField->render();
    }

    private function getSelectedImageTitle(): string {
        $selected_image_title = "";
        $selected_image = $this->imageElement->getImage();
        if (!is_null($selected_image)) {
            $selected_image_title = $selected_image->getTitle();
        }
        return $selected_image_title;
    }

    private function createFieldId($property_name): string {
        return "element_" . $this->imageElement->getId() . "_" . $property_name;
    }

}


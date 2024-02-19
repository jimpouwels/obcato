<?php
require_once CMS_ROOT . "/view/views/ElementVisual.php";
require_once CMS_ROOT . "/view/views/TextField.php";
require_once CMS_ROOT . "/view/views/ImagePicker.php";

class ImageElementEditor extends ElementVisual {

    private static string $TEMPLATE = "elements/image_element/image_element_form.tpl";
    private ImageElement $imageElement;

    public function __construct(TemplateEngine $templateEngine, ImageElement $element) {
        parent::__construct($templateEngine);
        $this->imageElement = $element;
    }

    public function getElement(): Element {
        return $this->imageElement;
    }

    public function getElementFormTemplateFilename(): string {
        return self::$TEMPLATE;
    }

    public function loadElementForm(Smarty_Internal_Data $data): void {
        $titleField = new TextField($this->getTemplateEngine(), $this->createFieldId("title"), $this->getTextResource("image_element_editor_title"), htmlentities($this->imageElement->getTitle()), false, false, null);
        $imagePicker = new ImagePicker($this->getTemplateEngine(), "image_image_ref_" . $this->imageElement->getId(), $this->getTextResource("image_element_editor_image"), $this->imageElement->getImageId(), "update_element_holder");
        $widthField = new TextField($this->getTemplateEngine(), $this->createFieldId("width"), $this->getTextResource("image_element_editor_width"), $this->imageElement->getWidth(), false, false, "size_field");
        $heightField = new TextField($this->getTemplateEngine(), $this->createFieldId("height"), $this->getTextResource("image_element_editor_height"), $this->imageElement->getHeight(), false, false, "size_field");

        $data->assign("alignment_field", $this->renderAlignmentField());
        $data->assign("title_field", $titleField->render());
        $data->assign("width_field", $widthField->render());
        $data->assign("height_field", $heightField->render());
        $data->assign("image_picker", $imagePicker->render());
        $data->assign("image_id", $this->imageElement->getImageId());
        $data->assign("selected_image_title", $this->getSelectedImageTitle());
    }

    private function renderAlignmentField(): string {
        $alignmentOptions = array();
        $alignmentOptions[] = array("name" => $this->getTextResource("image_element_align_left"), "value" => "left");
        $alignmentOptions[] = array("name" => $this->getTextResource("image_element_align_right"), "value" => "right");
        $alignmentOptions[] = array("name" => $this->getTextResource("image_element_align_center"), "value" => "center");
        $currentAlignment = $this->imageElement->getAlign();
        $alignmentField = new Pulldown($this->getTemplateEngine(), "element_" . $this->imageElement->getId() . "_align", $this->getTextResource("image_element_editor_alignment"), $currentAlignment, $alignmentOptions, false, null);
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


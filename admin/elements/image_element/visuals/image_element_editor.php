<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/view/views/ElementVisual.php";
require_once CMS_ROOT . "/view/views/TextField.php";
require_once CMS_ROOT . "/view/views/ImagePicker.php";

class ImageElementEditorVisual extends ElementVisual {

    private static string $TEMPLATE = "elements/image_element/image_element_form.tpl";
    private ImageElement $_image_element;

    public function __construct(ImageElement $image_element) {
        parent::__construct();
        $this->_image_element = $image_element;
    }

    public function getElement(): Element {
        return $this->_image_element;
    }

    public function renderElementForm(Smarty_Internal_Data $data): string {
        $title_field = new TextField($this->createFieldId("title"), $this->getTextResource("image_element_editor_title"), htmlentities($this->_image_element->getTitle()), false, false, null);
        $image_picker = new ImagePicker("image_image_ref_" . $this->_image_element->getId(), $this->getTextResource("image_element_editor_image"), $this->_image_element->getImageId(), "update_element_holder");
        $width_field = new TextField($this->createFieldId("width"), $this->getTextResource("image_element_editor_width"), $this->_image_element->getWidth(), false, false, "size_field");
        $height_field = new TextField($this->createFieldId("height"), $this->getTextResource("image_element_editor_height"), $this->_image_element->getHeight(), false, false, "size_field");

        $data->assign("alignment_field", $this->renderAlignmentField());
        $data->assign("title_field", $title_field->render());
        $data->assign("width_field", $width_field->render());
        $data->assign("height_field", $height_field->render());
        $data->assign("image_picker", $image_picker->render());
        $data->assign("image_id", $this->_image_element->getImageId());
        $data->assign("selected_image_title", $this->getSelectedImageTitle());
        return $this->getTemplateEngine()->fetch(self::$TEMPLATE, $data);
    }

    private function renderAlignmentField(): string {
        $alignment_options = array();
        array_push($alignment_options, array("name" => $this->getTextResource("image_element_align_left"), "value" => "left"));
        array_push($alignment_options, array("name" => $this->getTextResource("image_element_align_right"), "value" => "right"));
        array_push($alignment_options, array("name" => $this->getTextResource("image_element_align_center"), "value" => "center"));
        $current_alignment = $this->_image_element->getAlign();
        $alignment_field = new Pulldown("element_" . $this->_image_element->getId() . "_align", $this->getTextResource("image_element_editor_alignment"), $current_alignment, $alignment_options, false, null);
        return $alignment_field->render();
    }

    private function getSelectedImageTitle(): string {
        $selected_image_title = "";
        $selected_image = $this->_image_element->getImage();
        if (!is_null($selected_image)) {
            $selected_image_title = $selected_image->getTitle();
        }
        return $selected_image_title;
    }

    private function createFieldId($property_name): string {
        return "element_" . $this->_image_element->getId() . "_" . $property_name;
    }

}

?>

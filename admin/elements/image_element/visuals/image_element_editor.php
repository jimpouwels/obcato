<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/element_visual.php";
    require_once CMS_ROOT . "view/views/form_textfield.php";
    require_once CMS_ROOT . "view/views/image_picker.php";

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

        public function renderElementForm(): string {
            $title_field = new TextField($this->createFieldId("title"), $this->getTextResource("image_element_editor_title"), htmlentities($this->_image_element->getTitle()), false, false, null);
            $alternative_text_field = new TextField($this->createFieldId("alternative_text"), $this->getTextResource("image_element_editor_alternative_text"), $this->_image_element->getAlternativeText(), false, true, null);
            $image_picker = new ImagePicker($this->getTextResource("image_element_editor_image"), $this->_image_element->getImageId(), "image_image_ref_" . $this->_image_element->getId(), "update_element_holder", "");
            $width_field = new TextField($this->createFieldId("width"), $this->getTextResource("image_element_editor_width"), $this->_image_element->getWidth(), false, false, "size_field");
            $height_field = new TextField($this->createFieldId("height"), $this->getTextResource("image_element_editor_height"), $this->_image_element->getHeight(), false, false, "size_field");

            $this->getTemplateEngine()->assign("alignment_field", $this->renderAlignmentField());
            $this->getTemplateEngine()->assign("title_field", $title_field->render());
            $this->getTemplateEngine()->assign("alternative_text_field", $alternative_text_field->render());
            $this->getTemplateEngine()->assign("width_field", $width_field->render());
            $this->getTemplateEngine()->assign("height_field", $height_field->render());
            $this->getTemplateEngine()->assign("image_picker", $image_picker->render());
            $this->getTemplateEngine()->assign("image_id", $this->_image_element->getImageId());
            $this->getTemplateEngine()->assign("selected_image_title", $this->getSelectedImageTitle());
            return $this->getTemplateEngine()->fetch(self::$TEMPLATE);
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
                $selected_image_title = $selected_image->getTitle();;
            }
            return $selected_image_title;
        }

        private function createFieldId($property_name): string {
            return "element_" . $this->_image_element->getId() . "_" . $property_name;
        }

    }

?>

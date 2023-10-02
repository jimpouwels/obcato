<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "view/views/element_visual.php";
require_once CMS_ROOT . "view/views/form_textfield.php";
require_once CMS_ROOT . "view/views/form_date.php";
require_once CMS_ROOT . "view/views/image_label_selector.php";

class PhotoAlbumElementEditor extends ElementVisual {

    private static string $TEMPLATE = "elements/photo_album_element/photo_album_element_form.tpl";
    private PhotoAlbumElement $_element;

    public function __construct($_element) {
        parent::__construct();
        $this->_element = $_element;
    }

    public function getElement(): Element {
        return $this->_element;
    }

    public function renderElementForm(Smarty_Internal_Data $data): string {
        $title_field = new TextField("element_" . $this->_element->getId() . "_title", $this->getTextResource("photo_album_element_editor_title"), $this->_element->getTitle(), false, true, null);
        $max_results_field = new TextField("element_" . $this->_element->getId() . "_number_of_results", $this->getTextResource("photo_album_element_editor_max_results"), $this->_element->getNumberOfResults(), false, true, "number_of_results_field");
        $label_select_field = new ImageLabelSelector($this->_element->getLabels(), $this->_element->getId());

        $data->assign("title_field", $title_field->render());
        $data->assign("max_results_field", $max_results_field->render());
        $data->assign("label_select_field", $label_select_field->render());

        return $this->getTemplateEngine()->fetch(self::$TEMPLATE, $data);
    }

}

?>
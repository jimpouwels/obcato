<?php

namespace Obcato\Core\elements\photo_album_element\visuals;

use Obcato\Core\core\model\Element;
use Obcato\Core\elements\photo_album_element\PhotoAlbumElement;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\ElementVisual;
use Obcato\Core\view\views\ImageLabelSelector;
use Obcato\Core\view\views\ImageLookup;
use Obcato\Core\view\views\TextField;

class PhotoAlbumElementEditor extends ElementVisual {

    private static string $TEMPLATE = "photo_album_element/templates/photo_album_element_form.tpl";
    private PhotoAlbumElement $element;

    public function __construct($element) {
        parent::__construct();
        $this->element = $element;
    }

    public function getElement(): Element {
        return $this->element;
    }

    public function getElementFormTemplateFilename(): string {
        return self::$TEMPLATE;
    }

    public function loadElementForm(TemplateData $data): void {
        $titleField = new TextField("element_" . $this->element->getId() . "_title", $this->getTextResource("photo_album_element_editor_title"), $this->element->getTitle(), false, true, null);
        $maxResultsField = new TextField("element_" . $this->element->getId() . "_number_of_results", $this->getTextResource("photo_album_element_editor_max_results"), $this->element->getNumberOfResults(), false, true, "number_of_results_field");
        $imageLookupField = new ImageLookup("element_" . $this->element->getId() . "_image_search", $this->getTextResource("photo_album_element_editor_search_keyword"), "", $this->element->getId(), null);
        $labelSelectField = new ImageLabelSelector($this->element->getLabels(), $this->element->getId());

        $data->assign("element_id", $this->element->getId());
        $data->assign("title_field", $titleField->render());
        $data->assign("max_results_field", $maxResultsField->render());
        $data->assign("image_lookup_field", $imageLookupField->render());
        $data->assign("label_select_field", $labelSelectField->render());
    }

    public function includeLinkSelector(): bool
    {
        return false;
    }
}
<?php

namespace Obcato\Core;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;

class PhotoAlbumElementEditor extends ElementVisual {

    private static string $TEMPLATE = "elements/photo_album_element/photo_album_element_form.tpl";
    private PhotoAlbumElement $element;

    public function __construct(TemplateEngine $templateEngine, $element) {
        parent::__construct($templateEngine);
        $this->element = $element;
    }

    public function getElement(): Element {
        return $this->element;
    }

    public function getElementFormTemplateFilename(): string {
        return self::$TEMPLATE;
    }

    public function loadElementForm(TemplateData $data): void {
        $titleField = new TextField($this->getTemplateEngine(), "element_" . $this->element->getId() . "_title", $this->getTextResource("photo_album_element_editor_title"), $this->element->getTitle(), false, true, null);
        $maxResultsField = new TextField($this->getTemplateEngine(), "element_" . $this->element->getId() . "_number_of_results", $this->getTextResource("photo_album_element_editor_max_results"), $this->element->getNumberOfResults(), false, true, "number_of_results_field");
        $labelSelectField = new ImageLabelSelector($this->getTemplateEngine(), $this->element->getLabels(), $this->element->getId());

        $data->assign("title_field", $titleField->render());
        $data->assign("max_results_field", $maxResultsField->render());
        $data->assign("label_select_field", $labelSelectField->render());
    }

}
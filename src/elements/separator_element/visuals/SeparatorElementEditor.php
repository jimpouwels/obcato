<?php

namespace Obcato\Core\elements\separator_element\visuals;

use Obcato\Core\core\model\Element;
use Obcato\Core\elements\separator_element\SeparatorElement;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\ElementVisual;

class SeparatorElementEditor extends ElementVisual {

    private static string $TEMPLATE = "elements/separator_element/separator_element_form.tpl";
    private SeparatorElement $separatorElement;

    public function __construct(SeparatorElement $separatorElement) {
        parent::__construct();
        $this->separatorElement = $separatorElement;
    }

    public function getElement(): Element {
        return $this->separatorElement;
    }

    public function getElementFormTemplateFilename(): string {
        return self::$TEMPLATE;
    }

    public function loadElementForm(TemplateData $data): void {
    }

}

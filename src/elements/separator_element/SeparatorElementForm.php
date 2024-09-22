<?php

namespace Obcato\Core\elements\separator_element;

use Obcato\Core\request_handlers\ElementForm;

class SeparatorElementForm extends ElementForm {

    public function __construct(SeparatorElement $iframeElement) {
        parent::__construct($iframeElement);
    }

    public function loadFields(): void {
        parent::loadFields();
    }

}
<?php

namespace Obcato\Core\modules\webforms\visuals\webforms\fields;

use Obcato\Core\modules\webforms\model\WebformTextArea;
use Obcato\Core\view\TemplateData;

class WebformTextareaVisual extends WebformFieldVisual {

    public function __construct(?WebFormTextArea $form_field) {
        parent::__construct($form_field);
    }

    public function getFormFieldTemplate(): string {
        return "webforms/templates/webforms/fields/webform_textarea.tpl";
    }

    public function loadFieldContent(TemplateData $data): void {}
}

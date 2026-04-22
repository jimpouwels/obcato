<?php

namespace Pageflow\Core\modules\webforms\visuals\webforms\fields;

use Pageflow\Core\modules\webforms\model\WebformTextArea;
use Pageflow\Core\view\TemplateData;

class WebformTextareaVisual extends WebformFieldVisual {

    public function __construct(?WebFormTextArea $form_field) {
        parent::__construct($form_field);
    }

    public function getFormFieldTemplate(): string {
        return "webforms/templates/webforms/fields/webform_textarea.tpl";
    }

    public function loadFieldContent(TemplateData $data): void {}
}

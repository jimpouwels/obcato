<?php

namespace Obcato\Core\admin\modules\webforms\visuals\webforms\fields;

use Obcato\Core\admin\modules\webforms\model\WebformTextArea;
use Obcato\Core\admin\view\TemplateData;

class WebformTextareaVisual extends WebformFieldVisual {

    public function __construct(?WebFormTextArea $form_field) {
        parent::__construct($form_field);
    }

    public function getFormFieldTemplate(): string {
        return "modules/webforms/webforms/fields/webform_textarea.tpl";
    }

    public function loadFieldContent(TemplateData $data): void {}
}

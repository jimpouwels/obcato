<?php

namespace Obcato\Core\admin\frontend;

abstract class FormFieldVisual extends FormItemVisual {

    public function __construct(Page $page, ?Article $article, WebForm $webform, WebFormField $webformField) {
        parent::__construct($page, $article, $webform, $webformField);
    }

    public function getFormItemTemplateFilename(): string {
        return FRONTEND_TEMPLATE_DIR . '/form_field.tpl';
    }

    public function loadFormItem(): void {
        $mandatory = false;
        if ($this->getFormItem() instanceof WebFormField) {
            $mandatory = $this->getFormItem()->getMandatory();
        }
        $this->assign('value', FormStatus::getFieldValue($this->getFormItem()->getName()));
        $this->assign('mandatory', $mandatory);

        $this->loadFormField();
        $this->assign('form_field_html', $this->fetch($this->getFormFieldTemplateFilename()));
    }

    abstract function loadFormField(): void;

    abstract function getFormFieldTemplateFilename(): string;
}
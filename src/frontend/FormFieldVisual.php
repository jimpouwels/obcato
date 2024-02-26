<?php

namespace Obcato\Core\frontend;

use Obcato\Core\frontend\handlers\FormStatus;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\pages\model\Page;
use Obcato\Core\modules\webforms\model\Webform;
use Obcato\Core\modules\webforms\model\WebformField;
use const use Obcato\Core\FRONTEND_TEMPLATE_DIR;

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
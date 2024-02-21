<?php

namespace Obcato\Core\admin\view\views;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;
use Obcato\ComponentApi\Visual;
use Obcato\Core\admin\authentication\Session;
use Obcato\Core\admin\utilities\StringUtility;

abstract class FormField extends Visual {

    private ?string $cssClass;
    private string $fieldName;
    private ?string $labelResourceIdentifier;
    private bool $mandatory;
    private bool $linkable;
    private ?string $value;

    protected function __construct(TemplateEngine $templateEngine, string $field_name, ?string $value, ?string $label_resource_identifier, bool $mandatory, bool $linkable, ?string $css_class) {
        parent::__construct($templateEngine);
        $this->fieldName = $field_name;
        $this->cssClass = $css_class;
        $this->labelResourceIdentifier = $label_resource_identifier;
        $this->mandatory = $mandatory;
        $this->linkable = $linkable;
        $this->value = $value;
    }

    public function getTemplateFilename(): string {
        return "system/form_field.tpl";
    }

    abstract function getFormFieldTemplateFilename(): string;

    abstract function getFieldType(): string;

    abstract function loadFormField(TemplateData $data);

    public function load(): void {
        $this->assign("error", $this->getErrorHtml($this->fieldName));
        $this->assign('label', $this->getLabelHtml());
        $this->assign('type', $this->getFieldType());

        $fieldTemplateData = $this->createChildData();
        $this->loadFormField($fieldTemplateData);
        $fieldTemplateData->assign('classes', $this->getCssClassesHtml());
        $fieldTemplateData->assign("field_name", $this->fieldName);
        $fieldTemplateData->assign("field_value", $this->getFieldValue());
        $this->assign('form_field', $this->getTemplateEngine()->fetch($this->getFormFieldTemplateFilename(), $fieldTemplateData));
    }

    public function getErrorHtml(string $field_name): string {
        $error_html = "";
        if (Session::hasError($field_name)) {
            $error = new FormError($this->getTemplateEngine(), Session::popError($field_name));
            return $error->render();
        }
        return $error_html;
    }

    public function getCssClassesHtml(): string {
        $css_class_html = $this->cssClass;
        $css_class_html .= ' ' . $this->errorClass($this->fieldName);
        if ($this->linkable) {
            $css_class_html .= 'linkable ';
        }
        return trim($css_class_html);
    }

    public function errorClass(string $field_name): string {
        if (Session::hasError($field_name)) {
            return "invalid ";
        }
        return "";
    }

    protected function getInputLabelHtml(string $label_resource_identifier, string $field_name, bool $mandatory): string {
        if ($label_resource_identifier) {
            $label = new FormLabel($this->getTemplateEngine(), $field_name, $label_resource_identifier, $mandatory);
            return $label->render();
        }
        return "";
    }

    protected function getFieldName(): string {
        return $this->fieldName;
    }

    private function getFieldValue(): ?string {
        if (isset($_POST[$this->fieldName])) {
            return StringUtility::escapeXml($_POST[$this->fieldName]);
        } else {
            return StringUtility::escapeXml($this->value);
        }
    }

    private function getLabelHtml(): ?string {
        if ($this->labelResourceIdentifier) {
            return $this->getInputLabelHtml($this->labelResourceIdentifier, $this->fieldName, $this->mandatory);
        } else {
            return null;
        }
    }
}
<?php

use Obcato\ComponentApi\Session;

require_once CMS_ROOT . '/authentication/Session.php';
require_once CMS_ROOT . '/view/views/FormError.php';
require_once CMS_ROOT . '/view/views/FormLabel.php';

abstract class FormField extends Obcato\ComponentApi\Visual {

    private ?string $_css_class;
    private string $_field_name;
    private ?string $_label_resource_identifier;
    private bool $_mandatory;
    private bool $_linkable;
    private ?string $_value;

    protected function __construct(TemplateEngine $templateEngine, string $field_name, ?string $value, ?string $label_resource_identifier, bool $mandatory, bool $linkable, ?string $css_class) {
        parent::__construct($templateEngine);
        $this->_field_name = $field_name;
        $this->_css_class = $css_class;
        $this->_label_resource_identifier = $label_resource_identifier;
        $this->_mandatory = $mandatory;
        $this->_linkable = $linkable;
        $this->_value = $value;
    }

    public function getTemplateFilename(): string {
        return "system/form_field.tpl";
    }

    abstract function getFormFieldTemplateFilename(): string;

    abstract function getFieldType(): string;

    abstract function loadFormField(TemplateData $data);

    public function load(): void {
        $this->assign("error", $this->getErrorHtml($this->_field_name));
        $this->assign('label', $this->getLabelHtml());
        $this->assign('type', $this->getFieldType());

        $fieldTemplateData = $this->createChildData();
        $this->loadFormField($fieldTemplateData);
        $fieldTemplateData->assign('classes', $this->getCssClassesHtml());
        $fieldTemplateData->assign("field_name", $this->_field_name);
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
        $css_class_html = $this->_css_class;
        $css_class_html .= ' ' . $this->errorClass($this->_field_name);
        if ($this->_linkable) {
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
        return $this->_field_name;
    }

    private function getFieldValue(): ?string {
        if (isset($_POST[$this->_field_name])) {
            return StringUtility::escapeXml($_POST[$this->_field_name]);
        } else {
            return StringUtility::escapeXml($this->_value);
        }
    }

    private function getLabelHtml(): ?string {
        if ($this->_label_resource_identifier) {
            return $this->getInputLabelHtml($this->_label_resource_identifier, $this->_field_name, $this->_mandatory);
        } else {
            return null;
        }
    }
}
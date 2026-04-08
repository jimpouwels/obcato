<?php

namespace Obcato\Core\view\views;

use Obcato\Core\view\TemplateData;

class RichTextArea extends FormField {

    public function __construct(string $name, string $label, ?string $value, bool $mandatory, bool $linkable, ?string $className) {
        parent::__construct($name, $value, $label, $mandatory, $linkable, $className);
    }

    public function getFormFieldTemplateFilename(): string {
        return "form_rich_textarea.tpl";
    }

    public function loadFormField(TemplateData $data): void {
        // Convert legacy markdown to HTML for backward compatibility
        $htmlValue = $this->convertMarkdownToHtml($this->getValue());
        $data->assign("html_value", $htmlValue);
    }

    public function getFieldType(): string {
        return 'richtextarea';
    }

    private function convertMarkdownToHtml(?string $text): string {
        if (!$text) {
            return "";
        }

        // Check if content is already HTML (skip conversion if it is)
        $isHtml = preg_match('/<(strong|em|u|a|p|div|br|span)\b/i', $text);
        
        if ($isHtml) {
            // Already HTML, return as-is
            return $text;
        }

        // Legacy markdown content - convert it
        // Convert **text** to <strong>text</strong>
        $text = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text);
        
        // Convert __text__ to <em>text</em>
        $text = preg_replace('/__(.*?)__/', '<em>$1</em>', $text);
        
        // Convert [linktext](url) to <a href="url">linktext</a>
        // Note: external class and target="_blank" are added on frontend render
        $text = preg_replace('/\[([^\]]+)\]\(([^\)]+)\)/', '<a href="$2">$1</a>', $text);
        
        return $text;
    }
}

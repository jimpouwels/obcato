<?php

namespace Obcato\Core;

use TemplateEngine;

class WebformItemFactory {

    private array $_types = array();
    private static ?WebformItemFactory $_instance = null;

    private function __construct() {
        $this->addType(WebformTextField::$TYPE, "WebformTextfieldVisual", "WebformTextFieldForm", "FormTextFieldVisual");
        $this->addType(WebFormTextArea::$TYPE, "WebformTextAreaVisual", "WebformTextAreaForm", "FormTextAreaVisual");
        $this->addType(WebFormDropDown::$TYPE, "WebformDropDownVisual", "WebformDropDownForm", "FormDropDownVisual");
        $this->addType(WebFormButton::$TYPE, "WebformButtonVisual", "WebformButtonForm", "FormButtonVisual");
    }

    public static function getInstance(): WebformItemFactory {
        if (!self::$_instance) {
            self::$_instance = new WebformItemFactory();
        }
        return self::$_instance;
    }

    public function getBackendVisualFor(WebFormItem $webform_item): WebformItemVisual {
        $backend_visual_classname = $this->getFormItemType($webform_item->getType())->getBackendVisualClassname();
        return new $backend_visual_classname(TemplateEngine::getInstance(), $webform_item);
    }

    public function getBackendFormFor(WebFormItem $webform_item): WebformItemForm {
        $backend_form_classname = $this->getFormItemType($webform_item->getType())->getBackendFormClassname();
        return new $backend_form_classname($webform_item);
    }

    public function getFrontendVisualFor(WebForm $webform, WebFormItem $webform_item, Page $page, ?Article $article): FormItemVisual {
        $frontend_form_classname = $this->getFormItemType($webform_item->getType())->getFrontendVisualClassname();
        return new $frontend_form_classname($page, $article, $webform, $webform_item);
    }

    private function getFormItemType(string $type_to_find): FormItemType {
        $found_type = null;
        foreach ($this->_types as $type) {
            if ($type->getTypeName() == $type_to_find) {
                $found_type = $type;
            }
        }
        return $found_type;
    }

    private function addType(string $type_name, string $backend_visual_classname, string $backend_form_classname, string $frontend_visual_classname): void {
        $this->_types[] = new FormItemType($type_name, $backend_visual_classname, $backend_form_classname, $frontend_visual_classname);
    }
}
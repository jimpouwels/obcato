<?php

namespace Pageflow\Core\modules\webforms;

use Pageflow\Core\frontend\FormItemVisual;
use Pageflow\Core\modules\articles\model\Article;
use Pageflow\Core\modules\pages\model\Page;
use Pageflow\Core\modules\webforms\form\WebformItemForm;
use Pageflow\Core\modules\webforms\model\Webform;
use Pageflow\Core\modules\webforms\model\WebformButton;
use Pageflow\Core\modules\webforms\model\WebformDropdown;
use Pageflow\Core\modules\webforms\model\WebformItem;
use Pageflow\Core\modules\webforms\model\WebformTextArea;
use Pageflow\Core\modules\webforms\model\WebformTextfield;
use Pageflow\Core\modules\webforms\visuals\webforms\fields\WebformItemVisual;

class WebformItemFactory {

    private array $_types = array();
    private static ?WebformItemFactory $_instance = null;

    private function __construct() {
        $this->addType(WebformTextfield::$TYPE, "WebformTextfieldVisual", "WebformTextfieldForm", "FormTextfieldVisual");
        $this->addType(WebFormTextArea::$TYPE, "WebformTextareaVisual", "WebformTextAreaForm", "FormTextAreaVisual");
        $this->addType(WebformDropdown::$TYPE, "WebformDropDownVisual", "WebformDropDownForm", "FormDropDownVisual");
        $this->addType(WebformButton::$TYPE, "WebformButtonVisual", "WebformButtonForm", "FormButtonVisual");
    }

    public static function getInstance(): WebformItemFactory {
        if (!self::$_instance) {
            self::$_instance = new WebformItemFactory();
        }
        return self::$_instance;
    }

    public function getBackendVisualFor(WebformItem $webform_item): WebformItemVisual {
        $className = "Pageflow\\Core\\modules\\webforms\\visuals\\webforms\\fields\\" . $this->getFormItemType($webform_item->getType())->getBackendVisualClassname();
        return new $className($webform_item);
    }

    public function getBackendFormFor(WebformItem $webform_item): WebformItemForm {
        $className = "Pageflow\\Core\\modules\\webforms\\form\\" . $this->getFormItemType($webform_item->getType())->getBackendFormClassname();
        return new $className($webform_item);
    }

    public function getFrontendVisualFor(WebForm $webform, WebformItem $webform_item, Page $page, ?Article $article): FormItemVisual {
        $className = "Pageflow\\Core\\frontend\\" . $this->getFormItemType($webform_item->getType())->getFrontendVisualClassname();
        return new $className($page, $article, $webform, $webform_item);
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
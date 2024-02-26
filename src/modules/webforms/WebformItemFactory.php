<?php

namespace Obcato\Core\modules\webforms;

use Obcato\Core\frontend\FormItemVisual;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\pages\model\Page;
use Obcato\Core\modules\webforms\form\WebformItemForm;
use Obcato\Core\modules\webforms\model\Webform;
use Obcato\Core\modules\webforms\model\WebformButton;
use Obcato\Core\modules\webforms\model\WebformDropdown;
use Obcato\Core\modules\webforms\model\WebformItem;
use Obcato\Core\modules\webforms\model\WebformTextArea;
use Obcato\Core\modules\webforms\model\WebformTextfield;
use Obcato\Core\modules\webforms\visuals\webforms\fields\WebformItemVisual;

class WebformItemFactory {

    private array $_types = array();
    private static ?WebformItemFactory $_instance = null;

    private function __construct() {
        $this->addType(WebformTextfield::$TYPE, "WebformTextfieldVisual", "WebformTextfieldForm", "FormTextfieldVisual");
        $this->addType(WebFormTextArea::$TYPE, "WebformTextAreaVisual", "WebformTextAreaForm", "FormTextAreaVisual");
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
        $className = "Obcato\\Core\\modules\\webforms\\visuals\\webforms\\fields\\" . $this->getFormItemType($webform_item->getType())->getBackendVisualClassname();
        return new $className($webform_item);
    }

    public function getBackendFormFor(WebformItem $webform_item): WebformItemForm {
        $className = "Obcato\\Core\\modules\\webforms\\form\\" . $this->getFormItemType($webform_item->getType())->getBackendFormClassname();
        return new $className($webform_item);
    }

    public function getFrontendVisualFor(WebForm $webform, WebformItem $webform_item, Page $page, ?Article $article): FormItemVisual {
        $className = "Obcato\\Core\\frontend\\" . $this->getFormItemType($webform_item->getType())->getFrontendVisualClassname();
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
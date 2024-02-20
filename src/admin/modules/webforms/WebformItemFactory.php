<?php

namespace Obcato\Core\admin\modules\webforms;

use Obcato\Core\admin\frontend\FormItemVisual;
use Obcato\Core\admin\modules\articles\model\Article;
use Obcato\Core\admin\modules\pages\model\Page;
use Obcato\Core\admin\modules\webforms\form\WebformItemForm;
use Obcato\Core\admin\modules\webforms\model\Webform;
use Obcato\Core\admin\modules\webforms\model\WebformButton;
use Obcato\Core\admin\modules\webforms\model\WebformDropdown;
use Obcato\Core\admin\modules\webforms\model\WebformItem;
use Obcato\Core\admin\modules\webforms\model\WebformTextArea;
use Obcato\Core\admin\modules\webforms\model\WebformTextField;
use Obcato\Core\admin\modules\webforms\visuals\webforms\fields\WebformItemVisual;
use Obcato\Core\admin\view\TemplateEngine;

class WebformItemFactory {

    private array $_types = array();
    private static ?WebformItemFactory $_instance = null;

    private function __construct() {
        $this->addType(WebformTextField::$TYPE, "WebformTextfieldVisual", "WebformTextFieldForm", "FormTextFieldVisual");
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
        $className = "Obcato\\Core\\admin\\modules\\webforms\\visuals\\webforms\\fields\\" . $this->getFormItemType($webform_item->getType())->getBackendVisualClassname();
        return new $className(TemplateEngine::getInstance(), $webform_item);
    }

    public function getBackendFormFor(WebformItem $webform_item): WebformItemForm {
        $className = "Obcato\\Core\\admin\\modules\\webforms\\form\\" . $this->getFormItemType($webform_item->getType())->getBackendFormClassname();
        return new $className($webform_item);
    }

    public function getFrontendVisualFor(WebForm $webform, WebformItem $webform_item, Page $page, ?Article $article): FormItemVisual {
        $className = "Obcato\\Core\\admin\\frontend\\" . $this->getFormItemType($webform_item->getType())->getFrontendVisualClassname();
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
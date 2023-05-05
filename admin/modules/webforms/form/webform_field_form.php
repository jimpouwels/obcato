<?php

    defined("_ACCESS") or die;

    require_once CMS_ROOT . "core/form/form.php";
    require_once CMS_ROOT . "core/model/webform_item.php";
    require_once CMS_ROOT . 'modules/webforms/form/webform_item_form.php';

    abstract class WebFormFieldForm extends WebFormItemForm {

        public function __construct(WebFormField $webform_item) {
            parent::__construct($webform_item);
        }

        public function loadItemFields(): void {
           
        }

        public abstract function loadFieldFields(): void;

    }

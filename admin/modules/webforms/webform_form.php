<?php

    defined("_ACCESS") or die;

    require_once CMS_ROOT . "core/form/webform.php";

    class WebFormForm extends Form {

        private WebForm $_webform;

        public function __construct(WebForm $webform) {
            $this->_webform = $webform;
        }

        public function loadFields(): void {
            $this->_webform->setTitle($this->getMandatoryFieldValue("webform_title", "Titel is verplicht"));
            if ($this->hasErrors()) {
                throw new FormException();
            }
        }

    }

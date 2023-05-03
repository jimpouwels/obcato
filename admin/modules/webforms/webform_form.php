<?php

    defined("_ACCESS") or die;

    require_once CMS_ROOT . "core/form/form.php";

    class WebFormForm extends Form {

        private WebForm $_webform;

        public function __construct(WebForm $webform) {
            $this->_webform = $webform;
        }

        public function loadFields(): void {
            $this->_webform->setTitle($this->getMandatoryFieldValue("title", "webforms_editor_title_error_message"));
            if ($this->hasErrors()) {
                throw new FormException();
            }
        }

    }

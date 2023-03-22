<?php

    defined("_ACCESS") or die;

    require_once CMS_ROOT . "request_handlers/form.php";

    class ImageListForm extends Form {

        private $_image_id;

        public function loadFields() {
            $this->_image_id = $this->getMandatoryFieldValue("image_id", "");
            if ($this->hasErrors()) {
                throw new FormException();
            }
        }

        public function getImageId() {
            return $this->_image_id;
        }

    }

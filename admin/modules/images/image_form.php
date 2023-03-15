<?php

    defined("_ACCESS") or die;

    require_once CMS_ROOT . "request_handlers/form.php";

    class ImageForm extends Form {

        private $_image;
        private $_selected_labels;

        public function __construct($image) {
            $this->_image = $image;
        }

        public function loadFields() {
            $this->_image->setTitle($this->getMandatoryFieldValue("image_title", "Titel is verplicht"));
            $this->_image->setPublished($this->getCheckboxValue("image_published"));
            $this->_selected_labels = $this->getSelectValue("select_labels_" . $this->_image->getId());
            if ($this->hasErrors())
                throw new FormException();
        }

        public function getSelectedLabels() {
            return $this->_selected_labels;
        }

    }

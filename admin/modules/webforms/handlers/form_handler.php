<?php
    defined('_ACCESS') or die;
  
    abstract class FormHandler {

        private WebForm $_webform;

        public function __construct(WebForm $webform) {
            $this->_webform = $webform;
        }

        private array $_required_properties = array();

        abstract function getRequiredProperties(): array;

        abstract function handlePost(array $properties): void;

        abstract function getNameResourceIdentifier(): string;

        abstract function getType(): string;

        protected function getWebForm(): WebForm {
            return $this->_webform;
        }
    }
?>
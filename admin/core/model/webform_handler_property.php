<?php

    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'core/model/entity.php';

    class WebFormHandlerProperty extends Entity {

        private string $_name;
        private ?string $_value = null;
        private string $_type;

        public function setName(string $name): void {
            $this->_name = $name;
        }

        public function getName(): string {
            return $this->_name;
        }

        public function setValue(?string $value): void {
            $this->_value = $value;
        }

        public function getValue(): ?string {
            return $this->_value;
        }

        public function setType(string $type): void {
            $this->_type = $type;
        }

        public function getType(): string {
            return $this->_type;
        }

        public static function constructFromRecord(array $row): WebFormHandlerProperty {
            $webform_handler_property = new WebFormHandlerProperty();
            $webform_handler_property->initFromDb($row);
            return $webform_handler_property;
        }

        protected function initFromDb(array $row): void {
            $this->setName($row['name']);
            $this->setValue($row['value']);
            $this->setType($row['type']);
            parent::initFromDb($row);
        }
    }
?>
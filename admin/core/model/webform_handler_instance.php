<?php

    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'core/model/entity.php';

    class WebFormHandlerInstance extends Entity {

        private string $_type;
        private array $_properties;

        public function setType(string $type): void {
            $this->_type = $type;
        }

        public function getType(): string {
            return $this->_type;
        }

        public function setProperties(array $properties): void {
            $this->_properties = $properties;
        }

        public function getProperties(): array {
            return $this->_properties;
        }

        public function getProperty(string $property_to_find): ?WebFormHandlerProperty {
            foreach ($this->_properties as $property) {
                if ($property->getName() == $property_to_find) {
                    return $property;
                }
            }
            return null;
        }

        public static function constructFromRecord(array $row, array $properties): WebFormHandlerInstance {
            $webform_handler_instance = new WebFormHandlerInstance();
            $webform_handler_instance->setProperties($properties);
            $webform_handler_instance->initFromDb($row);
            return $webform_handler_instance;
        }

        protected function initFromDb(array $row): void {
            $this->setType($row['type']);
            parent::initFromDb($row);
        }
    }
?>
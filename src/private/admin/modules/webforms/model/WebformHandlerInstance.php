<?php
require_once CMS_ROOT . '/core/model/Entity.php';

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
        return Arrays::firstMatch($this->_properties, fn($p) => $property_to_find == $p->getName());
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
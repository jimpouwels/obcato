<?php
    defined('_ACCESS') or die;
  
    abstract class FormHandler {

        private array $_required_properties = array();

        abstract function getRequiredProperties(): array;

        abstract function handlePost(array $properties, array $fields): void;

        abstract function getNameResourceIdentifier(): string;

        abstract function getType(): string;

    }
?>
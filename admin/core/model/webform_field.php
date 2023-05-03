<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/entity.php";

    abstract class WebFormField extends Entity {
    
        private string $_label;
        private string $_name;
        private bool $_mandatory = false;

        public function __construct($label, $name, $mandatory) {
            $this->_label = $label;
            $this->_name = $name;
            $this->_mandatory = $mandatory;
        }
        
        public function setLabel(string $label): void {
            $this->_label = $label;
        }
        
        public function getLabel(): string {
            return $this->_label;
        }

        public function setName(string $label): void {
            $this->_label = $label;
        }

        public function getName(): string {
            return $this->_name;
        }

        public function setMandatory(bool $mandatory): void {
            $this->_mandatory = $mandatory;
        }

        public function getMandatory(): bool {
            return $this->_mandatory;
        }

        public abstract function getType(): string;

    }
    
?>
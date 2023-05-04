<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/presentable.php";

    abstract class WebFormField extends Presentable {
    
        private string $_label;
        private string $_name;
        private bool $_mandatory = false;

        public function __construct(int $scope_id, string $label, string $name, bool $mandatory) {
            parent::__construct($scope_id);
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
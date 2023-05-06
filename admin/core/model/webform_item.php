<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/presentable.php";

    abstract class WebFormItem extends Presentable {
    
        private string $_label;
        private string $_name;

        public function __construct(int $scope_id, string $label, string $name) {
            parent::__construct($scope_id);
            $this->_label = $label;
            $this->_name = $name;
        }
        
        public function setLabel(string $label): void {
            $this->_label = $label;
        }
        
        public function getLabel(): string {
            return $this->_label;
        }

        public function setName(string $name): void {
            $this->_name = $name;
        }

        public function getName(): string {
            return $this->_name;
        }

        public abstract function getType(): string;

    }
    
?>
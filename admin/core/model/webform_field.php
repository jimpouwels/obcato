<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/webform_item.php";

    abstract class WebFormField extends WebFormItem {
    
        private bool $_mandatory;

        public function __construct(int $scope_id, string $label, string $name, bool $mandatory) {
            parent::__construct($scope_id, $label, $name);
            $this->_mandatory = $mandatory;
        }
        
        public function setMandatory(bool $mandatory): void {
            $this->_mandatory = $mandatory;
        }

        public function getMandatory(): bool {
            return $this->_mandatory;
        }

    }
    
?>
<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/webform_item.php";

    abstract class WebFormField extends WebFormItem {
    
        private bool $_mandatory = false;
        
        public function setMandatory(bool $mandatory): void {
            $this->_mandatory = $mandatory;
        }

        public function getMandatory(): bool {
            return $this->_mandatory;
        }

        protected function initFromDb(array $row): void {
            $this->setMandatory($row["mandatory"] == 1 ? true : false);
            parent::initFromDb($row);
        }

    }
    
?>
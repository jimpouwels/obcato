<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/entity.php";

    class WebForm extends Entity {
    
        private string $_title;
        private array $_form_fields;
        
        public function setTitle(string $title): void {
            $this->_title = $title;
        }
        
        public function getTitle(): string {
            return $this->_title;
        }

        public function getFormFields(): array {
            return $this->_form_fields;
        }

        public function setFormFields(array $form_fields): void {
            $this->_form_fields = $form_fields;
        }
        
        public static function constructFromRecord(array $record, array $form_fields): WebForm {
            $form = new WebForm();
            $form->setId($record['id']);
            $form->setTitle($record['title']);
            $form->setFormFields($form_fields);
            return $form;
        }
    
    }
    
?>
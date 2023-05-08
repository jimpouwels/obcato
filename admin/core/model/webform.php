<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/entity.php";

    class WebForm extends Entity {
    
        private string $_title;
        private array $_form_fields;
        private bool $_include_captcha;
        
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

        public function setIncludeCaptcha(bool $include_captcha): void {
            $this->_include_captcha = $include_captcha;
        }

        public function getIncludeCaptcha(): bool {
            return $this->_include_captcha;
        }
        
        public static function constructFromRecord(array $record, array $form_fields): WebForm {
            $form = new WebForm();
            $form->setId($record['id']);
            $form->setTitle($record['title']);
            $form->setIncludeCaptcha($record['include_captcha'] == 1 ? true : false);
            $form->setFormFields($form_fields);
            return $form;
        }
    
    }
    
?>
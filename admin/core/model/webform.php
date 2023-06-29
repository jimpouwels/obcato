<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/entity.php";

    class WebForm extends Entity {
    
        private string $_title;
        private array $_form_fields;
        private bool $_include_captcha;
        private ?string $_captcha_key;
        
        public function setTitle(string $title): void {
            $this->_title = $title;
        }
        
        public function getTitle(): string {
            return $this->_title;
        }

        public function getFormFields(): array {
            usort($this->_form_fields, function(WebFormItem $f1, WebFormItem $f2) { return $f1->getOrderNr() - $f2->getOrderNr(); });
            return $this->_form_fields;
        }

        public function deleteWebFormItem(int $form_item_id): void {
            $this->_form_fields = array_filter($this->_form_fields, function($item) use($form_item_id) {
                return $item->getId() !== $form_item_id;
            }); 
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

        public function setCaptchaKey(?string $captcha_key): void {
            $this->_captcha_key = $captcha_key;
        }

        public function getCaptchaKey(): ?string {
            return $this->_captcha_key;
        }
        
        public static function constructFromRecord(array $record, array $form_fields): WebForm {
            $form = new WebForm();
            $form->setId($record['id']);
            $form->setTitle($record['title']);
            $form->setIncludeCaptcha($record['include_captcha'] == 1 ? true : false);
            $form->setCaptchaKey($record['captcha_key']);
            $form->setFormFields($form_fields);
            return $form;
        }
    
    }
    
?>
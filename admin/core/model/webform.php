<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/entity.php";

    class WebForm extends Entity {
    
        private string $_title;
        
        public function setTitle(string $title): void {
            $this->_title = $title;
        }
        
        public function getTitle(): string {
            return $this->_title;
        }
        
        public static function constructFromRecord(array $record): WebForm {
            $form = new WebForm();
            $form->setId($record['id']);
            $form->setTitle($record['title']);
            
            return $form;
        }
    
    }
    
?>
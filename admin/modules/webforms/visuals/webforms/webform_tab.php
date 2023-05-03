<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "modules/webforms/visuals/webforms/webform_list.php";
    require_once CMS_ROOT . "modules/webforms/visuals/webforms/webform_metadata_editor.php";
    require_once CMS_ROOT . "modules/webforms/visuals/webforms/webform_editor.php";
    
    class WebFormTab extends Visual {
    
        private ?WebForm $_current_webform;
        private WebFormRequestHandler $_webform_request_handler;
    
        public function __construct($webform_request_handler) {
            parent::__construct();
            $this->_webform_request_handler = $webform_request_handler;
            $this->_current_webform = $this->_webform_request_handler->getCurrentWebForm();
        }

        public function getTemplateFilename(): string {
            return "modules/webforms/webforms/root.tpl";
        }
    
        public function load(): void {
            $this->assign("list", $this->renderWebFormsList());
            if ($this->_current_webform) {
                $this->assign("metadata_editor", $this->renderMetadataEditor());
                $this->assign("webform_editor", $this->renderWebFormEditor());
            }
        }
        
        private function renderWebFormsList(): string {
            $webform_list = new WebFormList($this->_current_webform, $this->_webform_request_handler);
            return $webform_list->render();
        }

        private function renderMetadataEditor(): string {
            $metadata_editor = new WebFormMetadataEditor($this->_current_webform);
            return $metadata_editor->render();
        }

        private function renderWebFormEditor(): string {
            $webform_editor = new WebFormEditor($this->_current_webform);
            return $webform_editor->render();
        }
    }
    
?>
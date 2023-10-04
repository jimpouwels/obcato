<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/modules/webforms/visuals/webforms/WebformList.php";
require_once CMS_ROOT . "/modules/webforms/visuals/webforms/WebformMetadataEditor.php";
require_once CMS_ROOT . "/modules/webforms/visuals/webforms/WebformEditor.php";
require_once CMS_ROOT . "/modules/webforms/visuals/webforms/HandlersEditor.php";

class WebformTab extends Visual {

    private ?WebForm $_current_webform;
    private WebformRequestHandler $_webform_request_handler;

    public function __construct($webform_request_handler) {
        parent::__construct();
        $this->_webform_request_handler = $webform_request_handler;
        $this->_current_webform = $this->_webform_request_handler->getCurrentWebForm();
    }

    public function getTemplateFilename(): string {
        return "modules/webforms/webforms/root.tpl";
    }

    public function load(): void {
        $this->assign("action_form_id", ACTION_FORM_ID);
        $this->assign('list', $this->renderWebFormsList());
        if ($this->_current_webform) {
            $this->assign('id', $this->_current_webform->getId());
            $this->assign('metadata_editor', $this->renderMetadataEditor());
            $this->assign('webform_editor', $this->renderWebFormEditor());
            $this->assign('handlers_editor', $this->renderHandlersEditor());
        }
    }

    private function renderWebFormsList(): string {
        $webform_list = new WebformList($this->_current_webform, $this->_webform_request_handler);
        return $webform_list->render();
    }

    private function renderMetadataEditor(): string {
        $metadata_editor = new WebformMetadataEditor($this->_current_webform, $this);
        return $metadata_editor->render();
    }

    private function renderWebFormEditor(): string {
        $webform_editor = new WebformEditor($this->_current_webform);
        return $webform_editor->render();
    }

    private function renderHandlersEditor(): string {
        $handlers_editor = new HandlersEditor($this->_current_webform);
        return $handlers_editor->render();
    }
}

?>
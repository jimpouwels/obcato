<?php
require_once CMS_ROOT . "/modules/webforms/model/Webform.php";

class WebformList extends Panel {

    private ?WebForm $_current_webform;
    private WebformDao $_webform_dao;

    public function __construct(TemplateEngine $templateEngine, ?WebForm $current_webform, WebformRequestHandler $webform_request_handler) {
        parent::__construct($templateEngine, "webforms_list_panel_title", 'webforms_list');
        $this->_current_webform = $current_webform;
        $this->_webform_dao = WebformDaoMysql::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "modules/webforms/webforms/list.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $webforms = $this->_webform_dao->getAllWebForms();
        $webforms_data = array();
        foreach ($webforms as $webform) {
            $webform_data = array();
            $webform_data["id"] = $webform->getId();
            $webform_data["title"] = $webform->getTitle();
            $is_selected = false;
            if ($this->_current_webform) {
                $is_selected = $webform->getId() == $this->_current_webform->getId();
            }
            $webform_data["is_selected"] = $is_selected;
            $webforms_data[] = $webform_data;
        }
        $data->assign("webforms", $webforms_data);
    }

}

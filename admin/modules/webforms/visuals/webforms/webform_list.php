<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/webform.php";

    class WebFormList extends Panel {

        private ?WebForm $_current_webform;
        private WebFormDao $_webform_dao;
        private WebFormRequestHandler $_webform_request_handler;

        public function __construct(?WebForm $current_webform, WebFormRequestHandler $webform_request_handler) {
            parent::__construct("webforms_list_panel_title", 'webforms_list');
            $this->_current_webform = $current_webform;
            $this->_webform_request_handler = $webform_request_handler;
            $this->_webform_dao = WebFormDao::getInstance();
        }

        public function getPanelContentTemplate(): string {
            return "modules/webforms/webforms/list.tpl";
        }

        public function loadPanelContent(Smarty_Internal_Data $data): void {
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

<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/webform.php";

    class FormList extends Panel {

        private ?WebForm $_current_webform;
        private WebFormDao $_webform_dao;
        private WebFormRequestHandler $_webform_request_handler;

        public function __construct(?Image $current_webform, WebFormRequestHandler $webform_request_handler) {
            parent::__construct("webforms_list_panel_title", 'webforms_list');
            $this->_current_webform = $current_webform;
            $this->_webform_request_handler = $webform_request_handler;
            $this->_webform_dao = WebFormDao::getInstance();
        }

        public function getPanelContentTemplate(): string {
            return "modules/webforms/webforms/list.tpl";
        }

        public function loadPanelContent(Smarty_Internal_Data $data): void {
        }

    }

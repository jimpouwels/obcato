<?php    
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "database/dao/webform_dao.php";
    require_once CMS_ROOT . "request_handlers/http_request_handler.php";
    require_once CMS_ROOT . "modules/webforms/form/webform_form.php";
    require_once CMS_ROOT . "core/model/webform_textfield.php";
    require_once CMS_ROOT . "core/model/webform_textarea.php";
    
    class WebFormRequestHandler extends HttpRequestHandler {

        private static string $FORM_QUERYSTRING_KEY = "webform_id";

        private static string $FORM_ID_POST_KEY = "webform_id";
        private WebFormDao $_webform_dao;
        private ?WebForm $_current_webform = null;
    
        public function __construct() {
            $this->_webform_dao = WebFormDao::getInstance();
        }
    
        public function handleGet(): void {
            $this->_current_webform = $this->getFormFromGetRequest();
        }
        
        public function handlePost(): void {
            $this->_current_webform = $this->getFormFromPostRequest();
            if ($this->isAddWebFormAction()) {
                $this->addWebForm();
            } else if ($this->isAction("update_webform")) {
                $this->updateWebForm($this->_current_webform);
            } else if ($this->isAction("add_textfield")) {
                $this->addTextField($this->_current_webform);
                $this->updateWebForm($this->_current_webform);
            } else if ($this->isAction("add_textarea")) {
                $this->addTextArea($this->_current_webform);
                $this->updateWebForm($this->_current_webform);
            } else if ($this->isAction("add_button")) {
                $this->addButton($this->_current_webform);
                $this->updateWebForm($this->_current_webform);
            }
        }
        
        public function getCurrentWebForm(): ?WebForm {
            return $this->_current_webform;
        }
        
        private function getFormFromPostRequest(): ?WebForm {
            $webform = null;
            $webform_id = $this->getFormIdFromPostRequest();
            if (!is_null($webform_id)) {
                $webform = $this->_webform_dao->getWebForm($webform_id);
            }
            return $webform;
        }
        
        private function getFormFromGetRequest(): ?WebForm {
            $current_form = null;
            if (isset($_GET[self::$FORM_QUERYSTRING_KEY]) && $_GET[self::$FORM_QUERYSTRING_KEY] != "") {
                $current_form = $this->_webform_dao->getWebForm($_GET[self::$FORM_QUERYSTRING_KEY]);
            }
            return $current_form;
        }
        
        private function getFormIdFromPostRequest(): ?int {
            $form_id = null;
            if (isset($_POST[self::$FORM_ID_POST_KEY]) && $_POST[self::$FORM_ID_POST_KEY]) {
                $form_id = $_POST[self::$FORM_ID_POST_KEY];
            }
            return $form_id;
        }

        private function isAddWebFormAction(): bool {
            return isset($_POST["add_webform_action"]) && $_POST["add_webform_action"] == "add_webform";
        }

        private function isAction($name): bool {
            return isset($_POST["action"]) && $_POST["action"] == $name;
        }

        private function addWebForm(): void {
            $webform = new WebForm();
            $webform->setTitle($this->getTextResource("webforms_new_webform_title"));
            $this->_webform_dao->persistWebForm($webform);
            $this->sendSuccessMessage($this->getTextResource("webforms_new_webform_create_message"));
            $this->redirectTo($this->getBackendBaseUrl() . "&" . self::$FORM_QUERYSTRING_KEY . "=" . $webform->getId());
        }

        private function updateWebForm(WebForm $webform): void {
            $form = new WebFormForm($webform);
            try {
                $form->loadFields();
                $this->_webform_dao->updateWebForm($this->_current_webform);
                $this->sendSuccessMessage($this->getTextResource("webforms_update_success_message"));
            } catch (FormException $e) {
                $this->sendErrorMessage($this->getTextResource("webforms_update_error_message"));
            }
        }

        private function addTextField(WebForm $webform): void {
            $text_field = new WebFormTextField($this->getTextResource("webforms_new_textfield_label"), "textfield", false);
            $this->_webform_dao->persistWebFormField($webform, $text_field);
        }

        private function addTextArea(WebForm $webform): void {
            $text_area = new WebFormTextArea($this->getTextResource("webforms_new_textarea_label"), "textarea", false);
            $this->_webform_dao->persistWebFormField($webform, $text_area);
        }

        private function addButton(WebForm $webform): void {
            $button = new WebFormButton($this->getTextResource("webforms_new_button_label"), "button");
            $this->_webform_dao->persistWebFormField($webform, $button);
        }
        
    }
    
?>
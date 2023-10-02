<?php
defined("_ACCESS") or die;

require_once CMS_ROOT . 'core/model/page.php';
require_once CMS_ROOT . 'core/model/article.php';
require_once CMS_ROOT . 'database/dao/webform_dao.php';
require_once CMS_ROOT . 'database/dao/config_dao.php';
require_once CMS_ROOT . 'modules/webforms/webform_handler_manager.php';
require_once CMS_ROOT . 'frontend/handlers/form_status.php';
require_once CMS_ROOT . 'frontend/handlers/error_type.php';

class FormRequestHandler {

    private static ?FormRequestHandler $_instance = null;
    private WebFormHandlerManager $_webform_handler_manager;
    private WebFormDao $_webform_dao;
    private ConfigDao $_config_dao;

    public function __construct() {
        $this->_webform_dao = WebFormDao::getInstance();
        $this->_config_dao = ConfigDao::getInstance();
        $this->_webform_handler_manager = WebFormHandlerManager::getInstance();
    }

    public static function getInstance(): FormRequestHandler {
        if (!self::$_instance) {
            self::$_instance = new FormRequestHandler();
        }
        return self::$_instance;
    }

    public function handlePost(Page $page, ?Article $article): void {
        if (isset($_POST['webform_id'])) {
            $webform = $this->_webform_dao->getWebForm($_POST['webform_id']);

            if ($webform->getIncludeCaptcha() && !$this->validCaptcha()) {
                FormStatus::raiseError('captcha', ErrorType::InvalidValue);
            }
            $fields = $this->getFields($webform);

            if (FormStatus::hasErrors()) {
                return;
            }

            $handler_instances = $this->_webform_dao->getWebFormHandlersFor($webform);
            foreach ($handler_instances as $webform_handler_instance) {
                $webform_handler = $this->_webform_handler_manager->getHandler($webform_handler_instance->getType());
                $webform_handler->handlePost($webform_handler_instance, $fields, $page, $article);
            }

            FormStatus::setSubmittedForm($webform->getId());
        }
    }

    private function getFields(WebForm $webform): array {
        $form_fields = $this->_webform_dao->getWebFormItemsByWebForm($webform->getId());
        $filled_in_fields = array();
        foreach ($form_fields as $form_field) {
            if (!$form_field instanceof WebFormField) {
                continue;
            }
            if ($form_field->getMandatory() && empty($_POST[$form_field->getName()])) {
                FormStatus::raiseError($form_field->getName(), ErrorType::Mandatory);
            }
            $filled_in_field = array();
            $filled_in_field['name'] = $form_field->getName();
            $filled_in_field['value'] = $_POST[$form_field->getName()];
            $filled_in_fields[] = $filled_in_field;
        }
        return $filled_in_fields;
    }

    private function validCaptcha(): bool {
        $captcha_token = $_POST["captcha_token"];
        $secret_key = $this->_config_dao->getCaptchaSecret();
        $ip = $_SERVER['REMOTE_ADDR'];
        $url = "https://www.google.com/recaptcha/api/siteverify?secret={$secret_key}&response={$captcha_token}&remoteip={$ip}";

        $response = file_get_contents($url);
        $json_response = json_decode($response);

        return $json_response->success;
    }
}

?>
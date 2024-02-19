<?php
require_once CMS_ROOT . '/modules/pages/model/Page.php';
require_once CMS_ROOT . '/modules/articles/model/Article.php';
require_once CMS_ROOT . '/database/dao/WebformDaoMysql.php';
require_once CMS_ROOT . '/database/dao/ConfigDaoMysql.php';
require_once CMS_ROOT . '/modules/webforms/WebformHandlerManager.php';
require_once CMS_ROOT . '/frontend/handlers/FormStatus.php';
require_once CMS_ROOT . '/frontend/handlers/ErrorType.php';

class FormRequestHandler {

    private static ?FormRequestHandler $instance = null;
    private WebformHandlerManager $webformHandlerManager;
    private WebformDao $webformDao;
    private ConfigDao $configDao;

    public function __construct() {
        $this->webformDao = WebformDaoMysql::getInstance();
        $this->configDao = ConfigDaoMysql::getInstance();
        $this->webformHandlerManager = WebformHandlerManager::getInstance();
    }

    public static function getInstance(): FormRequestHandler {
        if (!self::$instance) {
            self::$instance = new FormRequestHandler();
        }
        return self::$instance;
    }

    public function handlePost(Page $page, ?Article $article): void {
        if (isset($_POST['webform_id'])) {
            $webform = $this->webformDao->getWebForm($_POST['webform_id']);

            if ($webform->getIncludeCaptcha() && !$this->validCaptcha()) {
                FormStatus::raiseError('captcha', ErrorType::InvalidValue);
            }
            $fields = $this->getFields($webform);

            if (FormStatus::hasErrors()) {
                return;
            }

            $handlerInstances = $this->webformDao->getWebFormHandlersFor($webform);
            foreach ($handlerInstances as $webform_handler_instance) {
                $webformHandler = $this->webformHandlerManager->getHandler($webform_handler_instance->getType());
                $webformHandler->handlePost($webform_handler_instance, $fields, $page, $article);
            }

            FormStatus::setSubmittedForm($webform->getId());
        }
    }

    private function getFields(WebForm $webform): array {
        $formFields = $this->webformDao->getWebFormItemsByWebForm($webform->getId());
        $filledInFields = array();
        foreach ($formFields as $formField) {
            if (!$formField instanceof WebFormField) {
                continue;
            }
            if ($formField->getMandatory() && empty($_POST[$formField->getName()])) {
                FormStatus::raiseError($formField->getName(), ErrorType::Mandatory);
            }
            $filledInField = array();
            $filledInField['name'] = $formField->getName();
            $filledInField['value'] = $_POST[$formField->getName()];
            $filledInFields[] = $filledInField;
        }
        return $filledInFields;
    }

    private function validCaptcha(): bool {
        $captchaToken = $_POST["captcha_token"];
        $secretKey = $this->configDao->getCaptchaSecret();
        $ip = $_SERVER['REMOTE_ADDR'];
        $url = "https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$captchaToken}&remoteip={$ip}";

        $response = file_get_contents($url);
        $jsonResponse = json_decode($response);

        return $jsonResponse->success;
    }
}
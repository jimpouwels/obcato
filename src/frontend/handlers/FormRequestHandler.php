<?php

namespace Obcato\Core\frontend\handlers;

use Obcato\Core\database\dao\ConfigDao;
use Obcato\Core\database\dao\ConfigDaoMysql;
use Obcato\Core\database\dao\WebformDao;
use Obcato\Core\database\dao\WebformDaoMysql;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\pages\model\Page;
use Obcato\Core\modules\webforms\model\Webform;
use Obcato\Core\modules\webforms\model\WebformField;
use Obcato\Core\modules\webforms\WebformHandlerManager;

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

            FormStatus::setSubmittedForm($webform->getId());

            $handlerInstances = $this->webformDao->getWebFormHandlersFor($webform);
            foreach ($handlerInstances as $webformHandlerInstance) {
                $webformHandler = $this->webformHandlerManager->getHandler($webformHandlerInstance->getType());
                $webformHandler->handlePost($webformHandlerInstance, $fields, $page, $article);
            }

            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
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
<?php

namespace Obcato\Core\admin\frontend\handlers;

use Obcato\Core\admin\database\dao\ConfigDao;
use Obcato\Core\admin\database\dao\ConfigDaoMysql;
use Obcato\Core\admin\database\dao\WebformDao;
use Obcato\Core\admin\database\dao\WebformDaoMysql;
use Obcato\Core\admin\modules\articles\model\Article;
use Obcato\Core\admin\modules\pages\model\Page;
use Obcato\Core\admin\modules\webforms\model\Webform;
use Obcato\Core\admin\modules\webforms\model\WebformField;
use Obcato\Core\admin\modules\webforms\WebformHandlerManager;

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
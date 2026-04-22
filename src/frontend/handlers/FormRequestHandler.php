<?php

namespace Pageflow\Core\frontend\handlers;

use Pageflow\Core\database\dao\ConfigDao;
use Pageflow\Core\database\dao\ConfigDaoMysql;
use Pageflow\Core\database\dao\WebformDao;
use Pageflow\Core\database\dao\WebformDaoMysql;
use Pageflow\Core\modules\articles\model\Article;
use Pageflow\Core\modules\pages\model\Page;
use Pageflow\Core\modules\webforms\model\Webform;
use Pageflow\Core\modules\webforms\model\WebformField;
use Pageflow\Core\modules\webforms\WebformHandlerManager;

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
                FormStatus::raiseError($webform->getId(), 'captcha', ErrorType::InvalidValue);
            }
            $fields = $this->getFields($webform);

            if (FormStatus::hasErrors($webform->getId())) {
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
                FormStatus::raiseError($webform->getId(), $formField->getName(), ErrorType::Mandatory);
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
        $url = "https://www.google.com/recaptcha/api/siteverify";
        $data = ['secret' => $secretKey, 'response' => $captchaToken, 'ip' => $ip];
        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ],
        ];

        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        $result = json_decode($response, true);
        foreach ($result as &$item) {
            return $item;
        }
        return false;
    }
}
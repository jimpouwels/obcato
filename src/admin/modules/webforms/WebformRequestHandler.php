<?php

namespace Obcato\Core;

class WebformRequestHandler extends HttpRequestHandler {

    private static string $FORM_QUERYSTRING_KEY = "webform_id";
    private static string $FORM_ID_POST_KEY = "webform_id";
    private WebformHandlerManager $_webform_handler_manager;
    private WebformDao $_webform_dao;
    private ?WebForm $_current_webform = null;
    private ConfigDao $_config_dao;

    public function __construct() {
        $this->_webform_dao = WebformDaoMysql::getInstance();
        $this->_config_dao = ConfigDaoMysql::getInstance();
        $this->_webform_handler_manager = WebformHandlerManager::getInstance();
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
        } else if ($this->isAction("delete_webform")) {
            $this->deleteWebForm($this->_current_webform);
        } else if ($this->isAction("add_textfield")) {
            $this->addTextField($this->_current_webform);
            $this->updateWebForm($this->_current_webform);
        } else if ($this->isAction("add_textarea")) {
            $this->addTextArea($this->_current_webform);
            $this->updateWebForm($this->_current_webform);
        } else if ($this->isAction("add_button")) {
            $this->addButton($this->_current_webform);
            $this->updateWebForm($this->_current_webform);
        } else if ($this->isAction("delete_form_item")) {
            $this->deleteFormItem(intval($_POST['webform_item_to_delete']));
            $this->updateWebForm($this->_current_webform);
        } else if ($this->isAction("add_handler_email_form_handler")) {
            $this->addWebFormHandler(EmailFormHandler::$TYPE);
            $this->updateWebForm($this->_current_webform);
        } else if ($this->isAction("add_handler_redirect_form_handler")) {
            $this->addWebFormHandler(RedirectFormHandler::$TYPE);
            $this->updateWebForm($this->_current_webform);
        } else if ($this->isAction("add_handler_article_comment_form_handler")) {
            $this->addWebFormHandler(ArticleCommentFormHandler::$TYPE);
            $this->updateWebForm($this->_current_webform);
        } else if ($this->isAction("delete_form_handler")) {
            $this->deleteFormHandler($this->_current_webform);
            $this->updateWebForm($this->_current_webform);
        }

        $this->_current_webform = $this->getFormFromPostRequest();
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
        $form = new WebformForm($webform);
        try {
            $form->loadFields();
            $this->_webform_dao->updateWebForm($this->_current_webform);
            foreach ($form->getHandlerProperties() as $property) {
                $this->_webform_dao->updateHandlerProperty($property);
            }
            if ($this->_current_webform->getIncludeCaptcha()) {
                $this->_config_dao->updateCaptchaSecret($form->getCaptchaSecret());
            }
            $this->sendSuccessMessage($this->getTextResource("webforms_update_success_message"));
        } catch (FormException) {
            $this->sendErrorMessage($this->getTextResource("webforms_update_error_message"));
        }
    }

    private function deleteWebForm(WebForm $webform): void {
        $this->_webform_dao->deleteWebForm($webform);
        $this->redirectTo($this->getBackendBaseUrl());
    }

    private function addTextField(WebForm $webform): void {
        $text_field = new WebformTextField();
        $this->_webform_dao->persistWebFormItem($webform, $text_field);
    }

    private function addTextArea(WebForm $webform): void {
        $text_area = new WebFormTextArea();
        $this->_webform_dao->persistWebFormItem($webform, $text_area);
    }

    private function addButton(WebForm $webform): void {
        $button = new WebFormButton();
        $this->_webform_dao->persistWebFormItem($webform, $button);
    }

    private function deleteFormItem(int $form_item_id): void {
        $this->_current_webform->deleteWebFormItem($form_item_id);
        $this->_webform_dao->deleteWebFormItem($form_item_id);
    }

    private function addWebFormHandler(string $type): void {
        $handler = $this->_webform_handler_manager->getHandler($type);
        $this->_webform_dao->addWebFormHandler($this->_current_webform, $handler);
    }

    private function deleteFormHandler(WebForm $webform): void {
        $this->_webform_dao->deleteWebFormHandler($webform, intval($_POST['webform_handler_to_delete']));
    }

}

?>
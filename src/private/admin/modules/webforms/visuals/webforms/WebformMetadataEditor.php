<?php
require_once CMS_ROOT . '/database/dao/ConfigDaoMysql.php';
require_once CMS_ROOT . "/view/views/TemplatePicker.php";

class WebformMetadataEditor extends Panel {

    private WebForm $_current_webform;
    private ConfigDao $_config_dao;

    public function __construct(TemplateEngine $templateEngine, WebForm $current_webform) {
        parent::__construct($templateEngine, 'webforms_metdata_editor_panel_title', '');
        $this->_current_webform = $current_webform;
        $this->_config_dao = ConfigDaoMysql::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return 'modules/webforms/webforms/metadata_editor.tpl';
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $title_text_field = new TextField($this->getTemplateEngine(), "title", "webforms_editor_title_field", $this->_current_webform->getTitle(), true, false, null);
        $data->assign("title_field", $title_text_field->render());

        $template_picker_field = new TemplatePicker($this->getTemplateEngine(), "template", $this->getTextResource("webforms_editor_template_field"), false, "", $this->_current_webform->getTemplate(), $this->_current_webform->getScope());
        $data->assign('template_picker', $template_picker_field->render());

        $captcha_key_field_class = "captcha_key_field_{$this->_current_webform->getId()}";
        $data->assign('captcha_key_field_class', $captcha_key_field_class);
        $data->assign('include_captcha', $this->_current_webform->getIncludeCaptcha());

        $captcha_key_field = new TextField($this->getTemplateEngine(), 'captcha_key', 'webforms_editor_captcha_key_field', $this->_current_webform->getCaptchaKey(), true, true, null);
        $data->assign('captcha_key_field', $captcha_key_field->render());

        $captcha_secret_field = new TextField($this->getTemplateEngine(), 'captcha_secret', 'webforms_editor_captcha_secret_field', $this->getCaptchaSecret(), true, true, null);
        $data->assign('captcha_secret_field', $captcha_secret_field->render());

        $captcha_checkbox = new SingleCheckbox($this->getTemplateEngine(), 'include_captcha', 'webforms_editor_captcha_field', $this->_current_webform->getIncludeCaptcha(), false, null);
        $captcha_checkbox->setOnChangeJS("onCaptchaChanged('{$captcha_key_field_class}')");
        $data->assign("include_captcha_field", $captcha_checkbox->render());
    }

    private function getCaptchaSecret(): ?string {
        return $this->_config_dao->getCaptchaSecret();
    }

}
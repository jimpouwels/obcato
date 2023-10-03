<?php

defined("_ACCESS") or die;

require_once CMS_ROOT . "/core/form/Form.php";
require_once CMS_ROOT . "/database/dao/PageDaoMysql.php";

class SettingsForm extends Form {

    private Settings $_settings;
    private PageDao $_page_dao;
    private int $_homepage_id;

    public function __construct(Settings $settings) {
        $this->_settings = $settings;
        $this->_page_dao = PageDaoMysql::getInstance();
    }

    public function loadFields(): void {
        $this->_settings->setWebsiteTitle($this->getMandatoryFieldValue("website_title"));
        $this->_settings->setFrontendHostname($this->getMandatoryFieldValue("frontend_hostname"));
        $this->_settings->setBackendHostname($this->getMandatoryFieldValue("backend_hostname"));
        $this->_settings->setCmsRootDir($this->preserveBackSlashes($this->getMandatoryFieldValue("cms_root_dir")));
        $this->_settings->setPublicRootDir($this->preserveBackSlashes($this->getMandatoryFieldValue("public_root_dir")));
        $this->_settings->setFrontendTemplateDir($this->preserveBackSlashes($this->getMandatoryFieldValue("frontend_template_dir")));
        $this->_settings->setSmtpHost($this->getFieldValue("smtp_host"));
        $this->_settings->setEmailAddress($this->getEmailAddress("email_address"));
        $this->_settings->setStaticDir($this->preserveBackSlashes($this->getMandatoryFieldValue("static_dir")));
        $this->_settings->setConfigDir($this->preserveBackSlashes($this->getMandatoryFieldValue("config_dir")));
        $this->_settings->setUploadDir($this->preserveBackSlashes($this->getMandatoryFieldValue("upload_dir")));
        $this->_settings->setComponentDir($this->preserveBackSlashes($this->getMandatoryFieldValue("component_dir")));
        $this->_settings->setBackendTemplateDir($this->preserveBackSlashes($this->getMandatoryFieldValue("backend_template_dir")));

        $selected_404_page_id = $this->getFieldValue("404_page_id");
        if ($selected_404_page_id) {
            $this->_settings->set404Page($this->_page_dao->getPage(intval($selected_404_page_id)));
        }
        $this->_homepage_id = intval($this->getMandatoryFieldValue("homepage_page_id"));

        if ($this->hasErrors()) {
            throw new FormException();
        }
    }

    public function getHomepageId(): int {
        return $this->_homepage_id;
    }

    private function preserveBackSlashes(string $value): string {
        return str_replace("\\", "\\\\", $value);
    }

}

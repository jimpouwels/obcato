<?php
require_once CMS_ROOT . "/core/form/Form.php";
require_once CMS_ROOT . "/modules/pages/service/PageInteractor.php";

class SettingsForm extends Form {

    private Settings $settings;
    private PageInteractor $pageService;
    private int $homepageId;

    public function __construct(Settings $settings) {
        $this->settings = $settings;
        $this->pageService = PageInteractor::getInstance();
    }

    public function loadFields(): void {
        $this->settings->setWebsiteTitle($this->getMandatoryFieldValue("website_title"));
        $this->settings->setFrontendHostname($this->getMandatoryFieldValue("frontend_hostname"));
        $this->settings->setBackendHostname($this->getMandatoryFieldValue("backend_hostname"));
        $this->settings->setSmtpHost($this->getFieldValue("smtp_host"));
        $this->settings->setEmailAddress($this->getEmailAddress("email_address"));

        $selected_404_page_id = $this->getFieldValue("404_page_id");
        if ($selected_404_page_id) {
            $this->settings->setPage404($this->pageService->getPageById(intval($selected_404_page_id)));
        }
        $this->homepageId = intval($this->getMandatoryFieldValue("homepage_page_id"));

        if ($this->hasErrors()) {
            throw new FormException();
        }
    }

    public function getHomepageId(): int {
        return $this->homepageId;
    }

    private function preserveBackSlashes(string $value): string {
        return str_replace("\\", "\\\\", $value);
    }

}

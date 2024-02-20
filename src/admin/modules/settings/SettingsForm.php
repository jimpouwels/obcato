<?php

namespace Obcato\Core\admin\modules\settings;

use Obcato\Core\admin\core\form\Form;
use Obcato\Core\admin\core\form\FormException;
use Obcato\Core\admin\modules\pages\service\PageInteractor;
use Obcato\Core\admin\modules\settings\model\Settings;

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

        $selected404PageId = $this->getFieldValue("404_page_id");
        if ($selected404PageId) {
            $this->settings->setPage404($this->pageService->getPageById(intval($selected404PageId)));
        }
        $this->homepageId = intval($this->getMandatoryFieldValue("homepage_page_id"));

        if ($this->hasErrors()) {
            throw new FormException();
        }
    }

    public function getHomepageId(): int {
        return $this->homepageId;
    }

}
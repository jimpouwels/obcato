<?php

namespace Obcato\Core\modules\settings;

use Obcato\Core\core\form\Form;
use Obcato\Core\core\form\FormException;
use Obcato\Core\modules\pages\service\PageInteractor;
use Obcato\Core\modules\pages\service\PageService;
use Obcato\Core\modules\settings\model\IFrameSecurityPolicy;
use Obcato\Core\modules\settings\model\Settings;

class SettingsForm extends Form {

    private Settings $settings;
    private PageService $pageService;
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
        $this->settings->setBrowserImageCacheInSeconds($this->getNumber("browser_image_cache_in_seconds"));
        $this->settings->setIFrameSecurityPolicy(IFrameSecurityPolicy::from($this->getNumber("iframe_security_policy")));
        $this->settings->setForceHttps($this->getBooleanValue("force_https"));

        $selected404PageId = $this->getNumber("404_page_id");
        if ($this->getFieldValue("delete_404_page_id") === "true") {
            $this->settings->setPage404(null);
        } else if ($selected404PageId) {
            $this->settings->setPage404($this->pageService->getPageById($selected404PageId));
        }
        $this->homepageId = $this->getNumber("homepage_page_id");

        if ($this->hasErrors()) {
            throw new FormException();
        }
    }

    public function getHomepageId(): int {
        return $this->homepageId;
    }

}
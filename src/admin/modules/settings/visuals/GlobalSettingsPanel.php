<?php

namespace Obcato\Core\admin\modules\settings\visuals;

use Obcato\Core\admin\modules\pages\service\PageInteractor;
use Obcato\Core\admin\modules\pages\service\PageService;
use Obcato\Core\admin\modules\settings\model\Settings;
use Obcato\Core\admin\view\TemplateData;
use Obcato\Core\admin\view\views\PagePicker;
use Obcato\Core\admin\view\views\Panel;
use Obcato\Core\admin\view\views\TextField;

class GlobalSettingsPanel extends Panel {

    private Settings $settings;
    private PageService $pageService;

    public function __construct(Settings $settings) {
        parent::__construct('Algemene instellingen');
        $this->settings = $settings;
        $this->pageService = PageInteractor::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "modules/settings/global_settings_panel.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $currentHomepage = $this->pageService->getHomepage();
        $current404Page = $this->settings->getPage404();

        $websiteTitle = new TextField("website_title", "settings_form_website_title_field", $this->settings->getWebsiteTitle(), true, false, null);
        $emailField = new TextField("email_address", "settings_form_website_email_address_field", $this->settings->getEmailAddress(), false, false, null);
        $homepagePicker = new PagePicker("homepage_page_id", "settings_form_website_homepage_field", $currentHomepage->getId(), "apply_settings");
        $picker404 = new PagePicker("404_page_id", "settings_form_404_page", $current404Page?->getId(), "apply_settings");

        $data->assign("website_title", $websiteTitle->render());
        $data->assign("email_field", $emailField->render());

        $data->assign("current_homepage_id", $currentHomepage->getId());
        $data->assign("current_homepage_title", $currentHomepage->getTitle());
        if ($current404Page) {
            $data->assign("current_404_page_id", $current404Page->getId());
            $data->assign("current_404_page_title", $current404Page->getTitle());
        }
        $data->assign("homepage_picker", $homepagePicker->render());
        $data->assign("page_404_picker", $picker404->render());
    }
}

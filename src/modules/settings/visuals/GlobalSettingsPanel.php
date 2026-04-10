<?php

namespace Obcato\Core\modules\settings\visuals;

use Obcato\Core\modules\pages\service\PageInteractor;
use Obcato\Core\modules\pages\service\PageService;
use Obcato\Core\modules\settings\model\Settings;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\PageLookup;
use Obcato\Core\view\views\Panel;
use Obcato\Core\view\views\TextField;

class GlobalSettingsPanel extends Panel {

    private Settings $settings;
    private PageService $pageService;

    public function __construct(Settings $settings) {
        parent::__construct('Algemene instellingen');
        $this->settings = $settings;
        $this->pageService = PageInteractor::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "settings/templates/global_settings_panel.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $currentHomepage = $this->pageService->getHomepage();
        $current404Page = $this->settings->getPage404();

        $websiteTitle = new TextField("website_title", "settings_form_website_title_field", $this->settings->getWebsiteTitle(), true, false, null);
        $emailField = new TextField("email_address", "settings_form_website_email_address_field", $this->settings->getEmailAddress(), false, false, null);
        $homepageLookup = new PageLookup(
            "homepage_page_id",
            "settings_form_website_homepage_field",
            (string)$currentHomepage->getId(),
            "settings_page_lookup_homepage_modal_title",
            "settings_page_lookup_homepage_selected_label",
            false
        );
        $picker404 = new PageLookup(
            "404_page_id",
            "settings_form_404_page",
            $current404Page ? (string)$current404Page->getId() : null,
            "settings_page_lookup_404_modal_title",
            "settings_page_lookup_404_selected_label",
            true,
            "delete_404_page_id"
        );

        $data->assign("website_title", $websiteTitle->render());
        $data->assign("email_field", $emailField->render());

        $data->assign("homepage_lookup", $homepageLookup->render());
        $data->assign("page_404_lookup", $picker404->render());
    }
}

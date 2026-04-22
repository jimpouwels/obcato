<?php

namespace Obcato\Core\modules\settings\visuals;

use Obcato\Core\modules\pages\service\PageInteractor;
use Obcato\Core\modules\pages\service\PageService;
use Obcato\Core\modules\settings\model\Settings;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\Panel;
use Obcato\Core\view\views\Pulldown;
use Obcato\Core\view\views\TextField;

class FrontendSettingsPanel extends Panel {

    private Settings $settings;

    public function __construct(Settings $settings) {
        parent::__construct('Frontend instellingen');
        $this->settings = $settings;
    }

    public function getPanelContentTemplate(): string {
        return "settings/templates/frontend_settings_panel.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $browserImageCacheInSeconds = new TextField("browser_image_cache_in_seconds", "settings_form_browser_image_cache_in_seconds", $this->settings->getBrowserImageCacheInSeconds(), true, false, null);
        $iFrameSecurityPolicy = new Pulldown("iframe_security_policy", "settings_form_iframe_security_policy", $this->settings->getIFrameSecurityPolicy(), [], true, null);
        $iFrameSecurityPolicy->addOption($this->getTextResource("settings_form_iframe_security_policy_sameorigin"), "SAMEORIGIN");
        $iFrameSecurityPolicy->addOption($this->getTextResource("settings_form_iframe_security_policy_allow"), "ALLOW");
        $iFrameSecurityPolicy->addOption($this->getTextResource("settings_form_iframe_security_policy_deny"), "DENY");

        $data->assign("browser_image_cache_in_seconds", $browserImageCacheInSeconds->render());
        $data->assign("iframe_security_policy", $iFrameSecurityPolicy->render());
    }
}

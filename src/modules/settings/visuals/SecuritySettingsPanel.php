<?php

namespace Pageflow\Core\modules\settings\visuals;

use Pageflow\Core\modules\settings\model\Settings;
use Pageflow\Core\view\TemplateData;
use Pageflow\Core\view\views\Panel;
use Pageflow\Core\view\views\Pulldown;
use Pageflow\Core\view\views\SingleCheckbox;
use Pageflow\Core\modules\settings\model\IFrameSecurityPolicy;

class SecuritySettingsPanel extends Panel {

    private Settings $settings;

    public function __construct(Settings $settings) {
        parent::__construct('Beveiligingsinstellingen');
        $this->settings = $settings;
    }

    public function getPanelContentTemplate(): string {
        return "settings/templates/security_settings_panel.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $iFrameSecurityPolicy = new Pulldown("iframe_security_policy", "settings_form_iframe_security_policy", $this->settings->getIFrameSecurityPolicy()->value, [], true, null);
        $iFrameSecurityPolicy->addOption($this->getTextResource("settings_form_iframe_security_policy_sameorigin"), IFrameSecurityPolicy::SAMEORIGIN->value);
        $iFrameSecurityPolicy->addOption($this->getTextResource("settings_form_iframe_security_policy_allow"), IFrameSecurityPolicy::ALLOW->value);
        $iFrameSecurityPolicy->addOption($this->getTextResource("settings_form_iframe_security_policy_deny"), IFrameSecurityPolicy::DENY->value);

        $forceHttps = new SingleCheckbox("force_https", "settings_form_force_https", $this->settings->isForceHttps() ? "on" : "", false, null);

        $data->assign("iframe_security_policy", $iFrameSecurityPolicy->render());
        $data->assign("force_https", $forceHttps->render());
    }
}

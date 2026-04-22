<?php

namespace Pageflow\Core\modules\settings\visuals;

use Pageflow\Core\modules\settings\model\Settings;
use Pageflow\Core\view\TemplateData;
use Pageflow\Core\view\views\Panel;
use Pageflow\Core\view\views\TextField;

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
        $data->assign("browser_image_cache_in_seconds", $browserImageCacheInSeconds->render());
    }
}

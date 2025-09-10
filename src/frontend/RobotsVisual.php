<?php

namespace Obcato\Core\frontend;

use Obcato\Core\modules\templates\model\Presentable;
use const Obcato\CMS_ROOT;

class RobotsVisual extends FrontendVisual {

    public function __construct() {
        parent::__construct(null, null);
    }

    public function getTemplateFilename(): string {
        return CMS_ROOT . "/frontend/templates/robots.tpl";
    }

    public function loadVisual(?array &$data): void {
        $this->assign('sitemap_url', $this->getLinkHelper()->createBaseUrl() . '/sitemap.xml');
    }

    public function getPresentable(): ?Presentable {
        return null;
    }

}
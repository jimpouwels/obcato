<?php

namespace Obcato\Core\admin\frontend;

class RobotsVisual extends FrontendVisual {

    public function __construct() {
        parent::__construct(null, null);
    }

    public function getTemplateFilename(): string {
        return FRONTEND_TEMPLATE_DIR . "/robots.tpl";
    }

    public function loadVisual(?array &$data): void {
        $this->assign('sitemap_url', $this->getBaseUrl() . '/sitemap.xml');
    }

    public function getPresentable(): ?Presentable {
        return null;
    }

}
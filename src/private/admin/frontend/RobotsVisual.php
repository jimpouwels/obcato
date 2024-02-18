<?php
require_once CMS_ROOT . "/frontend/FrontendVisual.php";
require_once CMS_ROOT . "/database/dao/PageDaoMysql.php";
require_once CMS_ROOT . "/database/dao/ArticleDaoMysql.php";

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
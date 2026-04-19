<?php

namespace Obcato\Core\frontend;

use Obcato\Core\frontend\helper\FrontendHelper;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\pages\model\Page;
use Obcato\Core\modules\templates\model\Presentable;
use Obcato\Core\database\dao\SettingsDao;
use Obcato\Core\database\dao\SettingsDaoMysql;
use const Obcato\CMS_ROOT;

class WebsiteVisual extends FrontendVisual {

    private SettingsDao $settingsDao;
    private ?Article $article;

    public function __construct(Page $page, ?Article $article) {
        parent::__construct($page, $article);
        $this->article = $article;
        $this->settingsDao = SettingsDaoMysql::getInstance();
    }

    public function getTemplateFilename(): string {
        return CMS_ROOT . "/frontend/templates/website.tpl";
    }

    public function loadVisual(?array &$data): void {
        $this->assignGlobal("is_preview", FrontendHelper::isPreviewMode());
        $this->assignGlobal("base_url", $this->getLinkHelper()->createBaseUrl());
        $this->assignGlobal("website_title", $this->settingsDao->getSettings()->getWebsiteTitle());
        $this->assignGlobal("meta_description", $this->article ? $this->article->getDescription() : $this->getPage()->getDescription());
        $pageVisual = new PageVisual($this->getPage(), $this->article);
        $this->assign("html", $pageVisual->render());
    }

    public function getPresentable(): ?Presentable {
        return null;
    }

}
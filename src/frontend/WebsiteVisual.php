<?php

namespace Pageflow\Core\frontend;

use Pageflow\Core\frontend\helper\FrontendHelper;
use Pageflow\Core\modules\articles\model\Article;
use Pageflow\Core\modules\pages\model\Page;
use Pageflow\Core\modules\templates\model\Presentable;
use Pageflow\Core\database\dao\SettingsDao;
use Pageflow\Core\database\dao\SettingsDaoMysql;
use const Pageflow\CMS_ROOT;

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
<?php

namespace Obcato\Core;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;

class TargetPagesList extends Panel {

    private ArticleDao $articleDao;

    public function __construct(TemplateEngine $templateEngine) {
        parent::__construct($templateEngine, 'Beschikbare doelpagina\'s', 'target_pages_fieldset');
        $this->articleDao = ArticleDaoMysql::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "modules/articles/target_pages/list.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign("target_pages", $this->getTargetPages());
        $data->assign("default_target_page", $this->getDefaultTargetPage());

        $pagePicker = new PagePicker($this->getTemplateEngine(), "add_target_page_ref", "", null, "update_target_pages");
        $data->assign("page_picker", $pagePicker->render());
    }

    private function getDefaultTargetPage(): ?array {
        $targetPage = $this->articleDao->getDefaultTargetPage();
        $targetPageValue = null;
        if (!is_null($targetPage)) {
            $targetPageValue = $this->toArray($targetPage);
        }
        return $targetPageValue;
    }

    private function getTargetPages(): array {
        $targetPages = array();
        foreach ($this->articleDao->getTargetPages() as $targetPage) {
            $targetPages[] = $this->toArray($targetPage);
        }
        return $targetPages;
    }

    private function toArray($targetPage): array {
        $targetPageValue = array();
        $targetPageValue["id"] = $targetPage->getId();
        $targetPageValue["title"] = $targetPage->getTitle();
        return $targetPageValue;
    }
}

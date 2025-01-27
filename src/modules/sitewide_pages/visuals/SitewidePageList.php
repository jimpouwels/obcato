<?php

namespace Obcato\Core\modules\sitewide_pages\visuals;

use Obcato\Core\modules\pages\service\PageInteractor;
use Obcato\Core\modules\pages\service\PageService;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\PagePicker;
use Obcato\Core\view\views\Panel;

class SitewidePageList extends Panel {

    private PageService $pageService;

    public function __construct() {
        parent::__construct('sitewide_pages_list_title', 'sitewide_pages_fieldset');
        $this->pageService = PageInteractor::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "modules/sitewide_pages/list.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign("sitewide_pages", $this->getSitewidePagesData());

        $pagePicker = new PagePicker("add_sitewide_page_ref", "", null, "update_sitewide_pages");
        $data->assign("page_picker", $pagePicker->render());
    }

    private function getSitewidePagesData(): array {
        $sitewidePages = array();
        foreach ($this->pageService->getSitewidePages() as $page) {
            $sitewidePages[] = $this->toArray($page);
        }
        return $sitewidePages;
    }

    private function toArray($page): array {
        $pageData = array();
        $pageData["id"] = $page->getId();
        $pageData["title"] = $page->getTitle();
        return $pageData;
    }
}

<?php

namespace Obcato\Core\modules\sitewide_pages;

use Obcato\Core\modules\pages\service\PageInteractor;
use Obcato\Core\modules\pages\service\PageService;
use Obcato\Core\request_handlers\HttpRequestHandler;
use function Obcato\Core\utilities\dumpVar;

class SitewidePagesRequestHandler extends HttpRequestHandler {

    private PageService $pageService;
    private SitewidePagesForm $sitewidePagesForm;

    public function __construct() {
        $this->pageService = PageInteractor::getInstance();
        $this->sitewidePagesForm = new SitewidePagesForm();
    }

    public function handleGet(): void {
    }

    public function handlePost(): void {
        $this->sitewidePagesForm->loadFields();
        if ($this->getAction() == "add_sitewide_page") {
            $this->pageService->addSitewidePage($this->sitewidePagesForm->getSitewidePageToAdd());
        }
        if ($this->getAction() == "remove_sitewide_pages") {
            $this->deleteSitewidePages();
        }
        if ($this->getAction() == "move_up") {
            $this->moveUp($this->sitewidePagesForm->getMovePage());
        } else if ($this->getAction() == "move_down") {
            $this->moveDown($this->sitewidePagesForm->getMovePage());
        }
    }

    private function deleteSitewidePages(): void {
        foreach ($this->sitewidePagesForm->getSitewidePagesToDelete() as $sitewidePageToDelete) {
            $this->pageService->removeSitewidePage($sitewidePageToDelete);
        }
    }

    private function getAction(): string {
        return $_POST["action"] ?? "";
    }

    private function moveUp(int $id): void {
        $pages = $this->pageService->getSitewidePages();
        for ($i = 0; $i < count($pages); $i++) {
            if ($pages[$i]->getId() == $id) {
                $tmp = $pages[$i - 1];
                $pages[$i - 1] = $pages[$i];
                $pages[$i] = $tmp;
                break;
            }
        }
        $this->pageService->updateSitewidePages($pages);
    }

    private function moveDown(int $id): void {
        $pages = $this->pageService->getSitewidePages();
        for ($i = 0; $i < count($pages); $i++) {
            if ($pages[$i]->getId() == $id) {
                $tmp = $pages[$i + 1];
                $pages[$i + 1] = $pages[$i];
                $pages[$i] = $tmp;
                break;
            }
        }
        $this->pageService->updateSitewidePages($pages);
    }
}
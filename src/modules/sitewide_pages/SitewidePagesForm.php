<?php

namespace Obcato\Core\modules\sitewide_pages;

use Obcato\Core\core\form\Form;
use Obcato\Core\database\dao\ArticleDao;
use Obcato\Core\database\dao\ArticleDaoMysql;
use Obcato\Core\modules\pages\service\PageInteractor;
use Obcato\Core\modules\pages\service\PageService;

class SitewidePagesForm extends Form {

    private ?int $sitewidePageToAdd;
    private ?int $movePage;
    private array $sitewidePagesToDelete = array();
    private PageService $pageService;

    public function __construct() {
        $this->pageService = PageInteractor::getInstance();
    }

    public function loadFields(): void {
        $this->sitewidePageToAdd = $this->getNumber("add_sitewide_page_ref");
        $this->movePage = $this->getNumber("moveSitewidePage");
        $this->loadSitewidePagesToDelete();
    }

    public function getSitewidePageToAdd(): int {
        return $this->sitewidePageToAdd;
    }

    public function getSitewidePagesToDelete(): array {
        return $this->sitewidePagesToDelete;
    }

    public function getMovePage(): ?int {
        return $this->movePage;
    }

    private function loadSitewidePagesToDelete(): void {
        $sitewidePages = $this->pageService->getSitewidePages();
        foreach ($sitewidePages as $sitewidePage) {
            $fieldToCheck = "sitewide_page_" . $sitewidePage->getId() . "_delete";
            if (isset($_POST[$fieldToCheck]) && $_POST[$fieldToCheck] != "") {
                $this->sitewidePagesToDelete[] = $sitewidePage;
            }
        }
    }

}
    
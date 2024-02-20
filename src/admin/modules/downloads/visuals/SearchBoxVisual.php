<?php

namespace Obcato\Core\admin\modules\downloads\visuals;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;
use Obcato\Core\admin\modules\downloads\DownloadRequestHandler;
use Obcato\Core\admin\view\views\Button;
use Obcato\Core\admin\view\views\Panel;
use Obcato\Core\admin\view\views\TextField;

class SearchBoxVisual extends Panel {

    private DownloadRequestHandler $downloadRequestHandler;

    public function __construct(TemplateEngine $templateEngine, DownloadRequestHandler $download_requestHandler) {
        parent::__construct($templateEngine, 'Zoeken', 'download_search');
        $this->downloadRequestHandler = $download_requestHandler;
    }

    public function getPanelContentTemplate(): string {
        return "modules/downloads/search_box.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign('search_query_field', $this->renderSearchQueryField());
        $data->assign('search_button', $this->renderSearchButton());
    }

    private function renderSearchQueryField(): string {
        $defaultSearchValue = $this->downloadRequestHandler->getSearchQuery();
        $searchQueryField = new TextField($this->getTemplateEngine(), 'search_query', 'Zoekterm', $defaultSearchValue, false, false, null);
        return $searchQueryField->render();
    }

    private function renderSearchButton(): string {
        $searchButton = new Button($this->getTemplateEngine(), '', 'Zoeken', 'document.getElementById(\'download_search\').submit(); return false;');
        return $searchButton->render();
    }
}

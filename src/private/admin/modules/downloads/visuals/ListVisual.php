<?php
require_once CMS_ROOT . "/database/dao/DownloadDaoMysql.php";
require_once CMS_ROOT . "/view/views/InformationMessage.php";

class ListVisual extends Panel {

    private DownloadDao $downloadDao;
    private DownloadRequestHandler $downloadRequestHandler;

    public function __construct(DownloadRequestHandler $download_requestHandler) {
        parent::__construct('Gevonden downloads', 'download_list');
        $this->downloadRequestHandler = $download_requestHandler;
        $this->downloadDao = DownloadDaoMysql::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "modules/downloads/list.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $data->assign("search_results", $this->getDownloads());
        $data->assign("no_results_message", $this->renderNoResultsMessage());
    }

    public function getDownloads(): array {
        if ($this->downloadRequestHandler->isSearchAction()) {
            return $this->getSearchResults();
        } else {
            return $this->getDownloadData($this->downloadDao->getAllDownloads());
        }
    }

    private function getSearchResults(): array {
        $searchQuery = $this->downloadRequestHandler->getSearchQuery();
        return $this->getDownloadData($this->downloadDao->searchDownloads($searchQuery));
    }

    private function getDownloadData(array $downloads): array {
        $downloadsValues = array();
        foreach ($downloads as $download) {
            $downloadValue = array();
            $downloadValue["id"] = $download->getId();
            $downloadValue["title"] = $download->getTitle();
            $downloadValue["published"] = $download->isPublished();
            $downloadValue["created_at"] = $download->getCreatedAt();
            $createdBy = $download->getCreatedBy();
            if (!is_null($createdBy))
                $downloadValue["created_by"] = $createdBy->getUsername();
            $downloadsValues[] = $downloadValue;
        }
        return $downloadsValues;
    }

    private function renderNoResultsMessage(): string {
        return (new InformationMessage("Geen downloads gevonden"))->render();
    }
}

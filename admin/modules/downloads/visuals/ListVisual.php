<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/database/dao/DownloadDaoMysql.php";
require_once CMS_ROOT . "/view/views/InformationMessage.php";

class ListVisual extends Panel {

    private DownloadDao $_download_dao;
    private DownloadRequestHandler $_download_request_handler;

    public function __construct(DownloadRequestHandler $download_request_handler) {
        parent::__construct('Gevonden downloads', 'download_list');
        $this->_download_request_handler = $download_request_handler;
        $this->_download_dao = DownloadDaoMysql::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "modules/downloads/list.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $data->assign("search_results", $this->getDownloads());
        $data->assign("no_results_message", $this->renderNoResultsMessage());
    }

    public function getDownloads(): array {
        if ($this->_download_request_handler->isSearchAction()) {
            return $this->getSearchResults();
        } else {
            return $this->getDownloadData($this->_download_dao->getAllDownloads());
        }
    }

    private function getSearchResults(): array {
        $search_query = $this->_download_request_handler->getSearchQuery();
        return $this->getDownloadData($this->_download_dao->searchDownloads($search_query));
    }

    private function getDownloadData(array $downloads): array {
        $downloads_values = array();
        foreach ($downloads as $download) {
            $download_value = array();
            $download_value["id"] = $download->getId();
            $download_value["title"] = $download->getTitle();
            $download_value["published"] = $download->isPublished();
            $download_value["created_at"] = $download->getCreatedAt();
            $created_by = $download->getCreatedBy();
            if (!is_null($created_by))
                $download_value["created_by"] = $created_by->getUsername();
            $downloads_values[] = $download_value;
        }
        return $downloads_values;
    }

    private function renderNoResultsMessage(): string {
        $message = new InformationMessage("Geen downloads gevonden");
        return $message->render();
    }
}

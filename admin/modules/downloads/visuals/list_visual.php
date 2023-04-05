<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/visual.php";
    require_once CMS_ROOT . "database/dao/download_dao.php";
    require_once CMS_ROOT . "view/views/information_message.php";

    class ListVisual extends Panel {

        private static $TEMPLATE = "downloads/list.tpl";
        private $_download_dao;
        private $_template_engine;
        private $_download_request_handler;

        public function __construct($download_request_handler) {
            parent::__construct('Gevonden downloads', 'download_list');
            $this->_download_request_handler = $download_request_handler;
            $this->_download_dao = DownloadDao::getInstance();
            $this->_template_engine = TemplateEngine::getInstance();
        }

        public function render(): string {
            return parent::render();
        }

        public function renderPanelContent() {
            $this->_template_engine->assign("search_results", $this->getDownloads());
            $this->_template_engine->assign("no_results_message", $this->renderNoResultsMessage());
            return $this->_template_engine->fetch("modules/" . self::$TEMPLATE);
        }

        public function getDownloads() {
            if ($this->_download_request_handler->isSearchAction())
                return $this->getSearchResults();
            else
                return $this->getDownloadData($this->_download_dao->getAllDownloads());
        }

        private function getSearchResults() {
            $search_query = $this->_download_request_handler->getSearchQuery();
            return $this->getDownloadData($this->_download_dao->searchDownloads($search_query));
        }

        private function getDownloadData($downloads) {
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

        private function renderNoResultsMessage() {
            $message = new InformationMessage("Geen downloads gevonden");
            return $message->render();
        }
    }

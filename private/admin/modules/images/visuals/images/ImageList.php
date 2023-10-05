<?php

class ImageList extends Panel {

    private ImageDao $_image_dao;
    private ImageRequestHandler $_images_request_handler;

    public function __construct(?Image $current_image, ImageRequestHandler $images_request_handler) {
        parent::__construct($this->getTextResource("images_list_panel_title"), 'images_list');
        $this->_images_request_handler = $images_request_handler;
        $this->_image_dao = ImageDaoMysql::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "modules/images/images/list.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $data->assign("search_results", $this->getSearchResults());
        $data->assign("action_form_id", ACTION_FORM_ID);
        $data->assign("no_results_message", $this->renderNoResultsMessage());
        $data->assign("current_search_title", $this->_images_request_handler->getCurrentSearchTitleFromGetRequest());
        $data->assign("current_search_filename", $this->_images_request_handler->getCurrentSearchFilenameFromGetRequest());
        $data->assign("current_search_label", $this->getCurrentSearchLabel());
    }

    private function getSearchResults(): array {
        $images = null;
        if ($this->isSearchAction()) {
            $images = $this->searchImages();
        } else if ($this->isNoLabelsSearchAction()) {
            $images = $this->_image_dao->getAllImagesWithoutLabel();
        } else {
            $images = $this->_image_dao->getAllImages();
        }
        return $this->toArray($images);
    }

    private function isSearchAction(): bool {
        return isset($_GET["action"]) && $_GET["action"] == "search";
    }

    private function searchImages(): array {
        $keyword = $this->_images_request_handler->getCurrentSearchTitleFromGetRequest();
        $filename = $this->_images_request_handler->getCurrentSearchFilenameFromGetRequest();
        $label = $this->_images_request_handler->getCurrentSearchLabelFromGetRequest();
        return $this->_image_dao->searchImages($keyword, $filename, $label);
    }

    private function isNoLabelsSearchAction(): bool {
        return isset($_GET["no_labels"]) && $_GET["no_labels"] == "true";
    }

    private function toArray(array $images): array {
        $image_values = array();
        foreach ($images as $image) {
            $image_value = array();
            $image_value["id"] = $image->getId();
            $image_value["title"] = $image->getTitle();
            $image_value["published"] = $image->isPublished();
            $image_value["created_at"] = $image->getCreatedAt();
            $image_value["thumb"] = $image->getThumbUrl();
            $created_by = $image->getCreatedBy();
            if (!is_null($created_by)) {
                $image_value["created_by"] = $created_by->getUsername();
            }
            $image_values[] = $image_value;
        }
        return $image_values;
    }

    private function renderNoResultsMessage(): string {
        $no_result_message = new InformationMessage("Geen afbeeldingen gevonden");
        return $no_result_message->render();
    }

    private function getCurrentSearchLabel(): ?string {
        $current_label_search_id = $this->_images_request_handler->getCurrentSearchLabelFromGetRequest();
        if (!is_null($current_label_search_id)) {
            return $this->_image_dao->getLabel($current_label_search_id)->getName();
        }
        return null;
    }

}

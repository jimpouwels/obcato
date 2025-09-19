<?php

namespace Obcato\Core\modules\images\visuals\images;

use Obcato\Core\database\dao\ImageDao;
use Obcato\Core\database\dao\ImageDaoMysql;
use Obcato\Core\modules\authorization\service\AuthorizationInteractor;
use Obcato\Core\modules\authorization\service\AuthorizationService;
use Obcato\Core\modules\images\ImageRequestHandler;
use Obcato\Core\utilities\ImageUtility;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\InformationMessage;
use Obcato\Core\view\views\Panel;
use const Obcato\Core\ACTION_FORM_ID;

class ImageList extends Panel {

    private ImageDao $imageDao;
    private ImageRequestHandler $requestHandler;
    private AuthorizationService $authorizationService;

    public function __construct(ImageRequestHandler $requestHandler) {
        parent::__construct($this->getTextResource("images_list_panel_title"), 'images_list');
        $this->requestHandler = $requestHandler;
        $this->authorizationService = AuthorizationInteractor::getInstance();
        $this->imageDao = ImageDaoMysql::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "images/templates/images/list.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign("search_results", $this->getSearchResults());
        $data->assign("action_form_id", ACTION_FORM_ID);
        $data->assign("no_results_message", $this->renderNoResultsMessage());
        $data->assign("current_search_title", $this->requestHandler->getCurrentSearchTitleFromGetRequest());
        $data->assign("current_search_filename", $this->requestHandler->getCurrentSearchFilenameFromGetRequest());
        $data->assign("current_search_label", $this->getCurrentSearchLabel());
    }

    private function getSearchResults(): array {
        if ($this->isSearchAction()) {
            $images = $this->searchImages();
        } else if ($this->isNoLabelsSearchAction()) {
            $images = $this->imageDao->getAllImagesWithoutLabel();
        } else {
            $images = $this->imageDao->getAllImages();
        }
        return $this->toArray($images);
    }

    private function isSearchAction(): bool {
        return isset($_GET["action"]) && $_GET["action"] == "search";
    }

    private function searchImages(): array {
        $keyword = $this->requestHandler->getCurrentSearchTitleFromGetRequest();
        $filename = $this->requestHandler->getCurrentSearchFilenameFromGetRequest();
        $label = $this->requestHandler->getCurrentSearchLabelFromGetRequest();
        return $this->imageDao->searchImages($keyword, $filename, $label);
    }

    private function isNoLabelsSearchAction(): bool {
        return isset($_GET["no_labels"]) && $_GET["no_labels"] == "true";
    }

    private function toArray(array $images): array {
        $imageValues = array();
        foreach ($images as $image) {
            $imageValue = array();
            $imageValue["id"] = $image->getId();
            $imageValue["title"] = $image->getTitle();
            $imageValue["has_mobile_version"] = ImageUtility::exists($image->getMobileFilename());
            $imageValue["published"] = $image->isPublished();
            $imageValue["created_at"] = $image->getCreatedAt();
            $imageValue["thumb"] = $image->getThumbUrl();
            $createdBy = $this->authorizationService->getUser($image->getCreatedById());
            $imageValue["created_by"] = $createdBy->getUsername();
            $imageValues[] = $imageValue;
        }
        return $imageValues;
    }

    private function renderNoResultsMessage(): string {
        $noResultsMessage = new InformationMessage("Geen afbeeldingen gevonden");
        return $noResultsMessage->render();
    }

    private function getCurrentSearchLabel(): ?string {
        $currentLabelSearchId = $this->requestHandler->getCurrentSearchLabelFromGetRequest();
        if ($currentLabelSearchId) {
            return $this->imageDao->getLabel($currentLabelSearchId)->getName();
        }
        return null;
    }

}

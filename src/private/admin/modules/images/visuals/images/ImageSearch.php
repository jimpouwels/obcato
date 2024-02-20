<?php

namespace Obcato\Core;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;

class ImageSearch extends Panel {

    private ImageDao $imageDao;
    private ImageRequestHandler $requestHandler;

    public function __construct(TemplateEngine $templateEngine, ImageRequestHandler $requestHandler) {
        parent::__construct($templateEngine, 'Zoeken', 'image_search');
        $this->imageDao = ImageDaoMysql::getInstance();
        $this->requestHandler = $requestHandler;
    }

    public function getPanelContentTemplate(): string {
        return "modules/images/images/search.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign("title_search_field", $this->getTitleSearchField()->render());
        $data->assign("filename_search_field", $this->getFileNameSearchField()->render());
        $data->assign("labels_search_field", $this->getLabelPullDown()->render());
        $data->assign("search_button", $this->getSearchButton()->render());
    }

    private function getTitleSearchField(): TextField {
        return new TextField(TemplateEngine::getInstance(), "s_title", "images_search_title_field", $this->requestHandler->getCurrentSearchTitleFromGetRequest(), false, false, null);
    }

    private function getFileNameSearchField(): TextField {
        return new TextField(TemplateEngine::getInstance(), "s_filename", "images_search_filename_field", $this->requestHandler->getCurrentSearchFilenameFromGetRequest(), false, false, null);
    }

    private function getLabelPullDown(): Pulldown {
        $labels = $this->getLabels();
        $currentlySelectedLabel = $this->requestHandler->getCurrentSearchLabelFromGetRequest();
        return new Pulldown(TemplateEngine::getInstance(), "s_label", "images_search_label_field", (is_null($currentlySelectedLabel) ? null : $currentlySelectedLabel), $labels, false, null, true);
    }

    private function getSearchButton(): Button {
        return new Button(TemplateEngine::getInstance(), "", "Zoeken", "document.getElementById('image_search').submit(); return false;");
    }

    private function getLabels(): array {
        $labelsNameValuePair = array();
        foreach ($this->imageDao->getAllLabels() as $label) {
            $labelsNameValuePair[] = array('name' => $label->getName(), 'value' => $label->getId());
        }
        return $labelsNameValuePair;
    }

}

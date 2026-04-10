<?php

namespace Obcato\Core\modules\images\visuals\images;

use Obcato\Core\database\dao\ImageDao;
use Obcato\Core\database\dao\ImageDaoMysql;
use Obcato\Core\modules\images\ImageRequestHandler;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\Button;
use Obcato\Core\view\views\Panel;
use Obcato\Core\view\views\TextField;

class ImageSearch extends Panel {

    private ImageDao $imageDao;
    private ImageRequestHandler $requestHandler;

    public function __construct(ImageRequestHandler $requestHandler) {
        parent::__construct('Zoeken', 'image_search');
        $this->imageDao = ImageDaoMysql::getInstance();
        $this->requestHandler = $requestHandler;
    }

    public function getPanelContentTemplate(): string {
        return "images/templates/images/search.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign("title_search_field", $this->getTitleSearchField()->render());
        $data->assign("filename_search_field", $this->getFileNameSearchField()->render());
        $data->assign("search_button", $this->getSearchButton()->render());
    }

    private function getTitleSearchField(): TextField {
        return new TextField("s_title", "images_search_title_field", $this->requestHandler->getCurrentSearchTitleFromGetRequest(), false, false, null);
    }

    private function getFileNameSearchField(): TextField {
        return new TextField("s_filename", "images_search_filename_field", $this->requestHandler->getCurrentSearchFilenameFromGetRequest(), false, false, null);
    }

    private function getSearchButton(): Button {
        return new Button("", "Zoeken", "document.getElementById('image_search').submit(); return false;");
    }

}

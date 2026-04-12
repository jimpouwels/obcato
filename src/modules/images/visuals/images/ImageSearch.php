<?php

namespace Obcato\Core\modules\images\visuals\images;

use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\Panel;

class ImageSearch extends Panel {

    public function __construct() {
        parent::__construct('', 'image_search');
    }

    public function getPanelContentTemplate(): string {
        return "images/templates/images/search.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
    }

}

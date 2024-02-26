<?php

namespace Obcato\Core\elements\article_overview_element\visuals;

use Obcato\Core\view\views\Visual;

class ArticleOverviewElementStatics extends Visual {

    public function __construct() {
        parent::__construct();
    }

    public function getTemplateFilename(): string {
        return "elements/article_overview_element/article_overview_element_statics.tpl";
    }

    public function load(): void {}

}

?>
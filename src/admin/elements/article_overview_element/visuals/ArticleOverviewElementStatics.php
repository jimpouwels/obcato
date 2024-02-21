<?php

namespace Obcato\Core\admin\elements\article_overview_element\visuals;

use Obcato\ComponentApi\TemplateEngine;
use Obcato\ComponentApi\Visual;

class ArticleOverviewElementStatics extends Visual {

    public function __construct(TemplateEngine $templateEngine) {
        parent::__construct($templateEngine);
    }

    public function getTemplateFilename(): string {
        return "elements/article_overview_element/article_overview_element_statics.tpl";
    }

    public function load(): void {}

}

?>
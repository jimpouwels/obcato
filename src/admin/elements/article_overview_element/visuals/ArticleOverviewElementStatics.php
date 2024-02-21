<?php

namespace Obcato\Core\admin\elements\article_overview_element\visuals;

use Obcato\Core\admin\view\views\Visual;

class ArticleOverviewElementStatics extends Visual {

    public function getTemplateFilename(): string {
        return "elements/article_overview_element/article_overview_element_statics.tpl";
    }

    public function load(): void {}

}

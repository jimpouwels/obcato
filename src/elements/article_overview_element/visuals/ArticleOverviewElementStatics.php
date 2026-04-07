<?php

namespace Obcato\Core\elements\article_overview_element\visuals;

use Obcato\Core\view\views\ElementStatic;

class ArticleOverviewElementStatics extends ElementStatic {

    public function __construct() {
        parent::__construct();
    }

    public function renderStyles(): array {
        $styles = array();
        $styles[] = $this->getTemplateEngine()->fetch("article_overview_element/templates/styles/article_overview_element.css.tpl");
        return $styles;
    }

    public function renderScripts(): array {
        return array();
    }

}
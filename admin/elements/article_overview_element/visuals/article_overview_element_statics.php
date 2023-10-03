<?php

defined('_ACCESS') or die;

require_once CMS_ROOT . "/view/views/Visual.php";

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
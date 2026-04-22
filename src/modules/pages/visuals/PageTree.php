<?php

namespace Pageflow\Core\modules\pages\visuals;

use Pageflow\Core\modules\pages\model\Page;
use Pageflow\Core\view\TemplateData;
use Pageflow\Core\view\views\Panel;

class PageTree extends Panel {

    private Page $rootPage;
    private Page $selectedPage;

    public function __construct(Page $rootPage, Page $selectedPage) {
        parent::__construct($this->getTextResource('page_tree_title'), 'page_tree_panel');
        $this->rootPage = $rootPage;
        $this->selectedPage = $selectedPage;
    }

    public function getPanelContentTemplate(): string {
        return "pages/templates/tree.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $rootTreeItem = new PageTreeItem($this->rootPage, $this->selectedPage);
        $data->assign("items_html", $rootTreeItem->render());
    }

}
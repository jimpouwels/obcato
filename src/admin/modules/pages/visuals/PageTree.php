<?php

namespace Obcato\Core\admin\modules\pages\visuals;

use Obcato\ComponentApi\TemplateData;
use Obcato\Core\admin\modules\pages\model\Page;
use Obcato\Core\admin\view\views\Panel;

class PageTree extends Panel {

    private Page $rootPage;
    private Page $selectedPage;

    public function __construct(Page $rootPage, Page $selectedPage) {
        parent::__construct($this->getTextResource('page_tree_title'), 'page_tree_panel');
        $this->rootPage = $rootPage;
        $this->selectedPage = $selectedPage;
    }

    public function getPanelContentTemplate(): string {
        return "modules/pages/tree.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $rootTreeItem = new PageTreeItem($this->rootPage, $this->selectedPage);
        $data->assign("items_html", $rootTreeItem->render());
    }

}
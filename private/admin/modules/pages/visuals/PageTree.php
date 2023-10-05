<?php
require_once CMS_ROOT . "/modules/pages/visuals/PageTreeItem.php";

class PageTree extends Panel {

    private Page $_root_page;
    private Page $_selected_page;

    public function __construct(Page $root_page, Page $selected_page) {
        parent::__construct($this->getTextResource('page_tree_title'), 'page_tree_panel');
        $this->_root_page = $root_page;
        $this->_selected_page = $selected_page;
    }

    public function getPanelContentTemplate(): string {
        return "modules/pages/tree.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $root_tree_item = new PageTreeItem($this->_root_page, $this->_selected_page);
        $data->assign("items_html", $root_tree_item->render());
    }

}

?>

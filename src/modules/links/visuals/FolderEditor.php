<?php

namespace Pageflow\Core\modules\links\visuals;

use Pageflow\Core\modules\links\model\ReusableLinkFolder;
use Pageflow\Core\view\TemplateData;
use Pageflow\Core\view\views\Panel;
use Pageflow\Core\view\views\TextField;

class FolderEditor extends Panel {

    private ReusableLinkFolder $folder;

    public function __construct(ReusableLinkFolder $folder) {
        parent::__construct('links_editor_folder_panel_title', 'folder_editor_panel');
        $this->folder = $folder;
    }

    public function getPanelContentTemplate(): string {
        return "links/templates/folder_editor.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $nameField = new TextField('name', 'links_editor_folder_name_label', $this->folder->getName(), true, false, null);

        $data->assign('folder_id',  $this->folder->getId());
        $data->assign('name_field', $nameField->render());
    }
}

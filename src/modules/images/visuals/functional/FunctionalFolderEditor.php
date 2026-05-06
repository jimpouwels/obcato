<?php

namespace Pageflow\Core\modules\images\visuals\functional;

use Pageflow\Core\modules\images\model\FunctionalImageFolder;
use Pageflow\Core\view\TemplateData;
use Pageflow\Core\view\views\Panel;
use Pageflow\Core\view\views\TextField;

class FunctionalFolderEditor extends Panel {

    private FunctionalImageFolder $folder;

    public function __construct(FunctionalImageFolder $folder) {
        parent::__construct($this->getTextResource('functional_images_folder_editor_panel_title'), 'functional_image_folder_editor');
        $this->folder = $folder;
    }

    public function getPanelContentTemplate(): string {
        return "images/templates/functional/folder_editor.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $nameField = new TextField('fimg_folder_name', $this->getTextResource('functional_images_folder_name_label'), $this->folder->getName(), true, false, null);
        $data->assign('folder_id',  $this->folder->getId());
        $data->assign('name_field', $nameField->render());
    }
}

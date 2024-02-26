<?php

namespace Obcato\Core\modules\images\visuals\labels;

use Obcato\Core\modules\images\model\ImageLabel;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\Panel;
use Obcato\Core\view\views\TextField;

class LabelEditor extends Panel {

    private ImageLabel $_current_label;

    public function __construct(ImageLabel $current_label) {
        parent::__construct('Label bewerken');
        $this->_current_label = $current_label;
    }

    public function getPanelContentTemplate(): string {
        return "modules/images/labels/editor.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign("id", $this->_current_label->getId());
        $data->assign("label_name_field", $this->renderLabelNameField());
    }

    private function renderLabelNameField(): string {
        $name_field = new TextField("name", "Naam", $this->_current_label->getName(), true, false, null);
        return $name_field->render();
    }
}

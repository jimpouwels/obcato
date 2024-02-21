<?php

namespace Obcato\Core\admin\modules\images\visuals\labels;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;
use Obcato\Core\admin\modules\images\model\ImageLabel;
use Obcato\Core\admin\view\views\Panel;
use Obcato\Core\admin\view\views\TextField;

class LabelEditor extends Panel {

    private ImageLabel $_current_label;

    public function __construct( ImageLabel $current_label) {
        parent::__construct( 'Label bewerken');
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

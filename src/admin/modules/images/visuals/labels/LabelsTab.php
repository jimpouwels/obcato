<?php

namespace Obcato\Core\admin\modules\images\visuals\labels;

use Obcato\Core\admin\modules\images\LabelRequestHandler;
use Obcato\Core\admin\modules\images\model\ImageLabel;
use Obcato\Core\admin\view\views\Visual;

class LabelsTab extends Visual {

    private ?ImageLabel $_current_label;
    private LabelRequestHandler $_label_request_handler;

    public function __construct(LabelRequestHandler $label_requestHandler) {
        parent::__construct();
        $this->_label_request_handler = $label_requestHandler;
        $this->_current_label = $this->_label_request_handler->getCurrentLabel();
    }

    public function getTemplateFilename(): string {
        return "modules/images/labels/root.tpl";
    }

    public function load(): void {
        if (!is_null($this->_current_label)) {
            $this->assign("label_editor", $this->renderLabelEditor());
        }
        $this->assign("labels_list", $this->renderLabelsList());
    }

    private function renderLabelEditor(): string {
        $label_editor = new LabelEditor($this->_current_label);
        return $label_editor->render();
    }

    private function renderLabelsList(): string {
        $labels_list = new LabelsList();
        return $labels_list->render();
    }

}
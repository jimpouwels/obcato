<?php
require_once CMS_ROOT . "/modules/images/visuals/labels/LabelsList.php";
require_once CMS_ROOT . "/modules/images/visuals/labels/LabelEditor.php";

class LabelsTab extends Obcato\ComponentApi\Visual {

    private ?ImageLabel $_current_label;
    private LabelRequestHandler $_label_request_handler;

    public function __construct(TemplateEngine $templateEngine, LabelRequestHandler $label_requestHandler) {
        parent::__construct($templateEngine);
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
        $label_editor = new LabelEditor($this->getTemplateEngine(), $this->_current_label);
        return $label_editor->render();
    }

    private function renderLabelsList(): string {
        $labels_list = new LabelsList($this->getTemplateEngine());
        return $labels_list->render();
    }

}
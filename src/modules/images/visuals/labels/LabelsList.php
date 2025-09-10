<?php

namespace Obcato\Core\modules\images\visuals\labels;

use Obcato\Core\database\dao\ImageDao;
use Obcato\Core\database\dao\ImageDaoMysql;
use Obcato\Core\modules\images\model\ImageLabel;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\InformationMessage;
use Obcato\Core\view\views\Panel;
use Obcato\Core\view\views\SingleCheckbox;

class LabelsList extends Panel {

    private ImageDao $_image_dao;

    public function __construct() {
        parent::__construct('Labels');
        $this->_image_dao = ImageDaoMysql::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "images/templates/labels/list.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign("all_labels", $this->getAllLabels());
        $data->assign("no_labels_message", $this->getNoLabelsMessage());
    }

    private function getAllLabels(): array {
        $label_values = array();
        $all_labels = $this->_image_dao->getAllLabels();
        foreach ($all_labels as $label) {
            $label_values[] = $this->createLabelValue($label);
        }
        return $label_values;
    }

    private function getNoLabelsMessage(): string {
        $message = new InformationMessage("Geen labels gevonden");
        return $message->render();
    }

    private function createLabelValue(ImageLabel $label): array {
        $label_value = array();
        $label_value["id"] = $label->getId();
        $label_value["name"] = $label->getName();
        $label_value["delete_checkbox"] = $this->getDeleteCheckBox($label);
        return $label_value;
    }

    private function getDeleteCheckBox(ImageLabel $label): string {
        $checkbox = new SingleCheckBox("label_" . $label->getId() . "_delete", "", 0, false, "");
        return $checkbox->render();
    }
}

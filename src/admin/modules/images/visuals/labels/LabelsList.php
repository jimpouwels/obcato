<?php

namespace Obcato\Core\admin\modules\images\visuals\labels;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;
use Obcato\Core\admin\database\dao\ImageDao;
use Obcato\Core\admin\database\dao\ImageDaoMysql;
use Obcato\Core\admin\modules\images\model\ImageLabel;
use Obcato\Core\admin\view\views\InformationMessage;
use Obcato\Core\admin\view\views\Panel;
use Obcato\Core\admin\view\views\SingleCheckbox;

class LabelsList extends Panel {

    private ImageDao $_image_dao;

    public function __construct(TemplateEngine $templateEngine) {
        parent::__construct($templateEngine, 'Labels');
        $this->_image_dao = ImageDaoMysql::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "modules/images/labels/list.tpl";
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
        $message = new InformationMessage($this->getTemplateEngine(), "Geen labels gevonden");
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
        $checkbox = new SingleCheckBox($this->getTemplateEngine(), "label_" . $label->getId() . "_delete", "", 0, false, "");
        return $checkbox->render();
    }
}

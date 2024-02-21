<?php

namespace Obcato\Core\admin\view\views;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;
use Obcato\Core\admin\database\dao\ImageDao;
use Obcato\Core\admin\database\dao\ImageDaoMysql;
use Obcato\Core\admin\modules\images\model\ImageLabel;

class ImageLabelSelector extends Panel {

    private array $_selected_labels;
    private ImageDao $_image_dao;
    private int $_context_id;

    public function __construct( array $selected_labels, int $contextId) {
        parent::__construct('Labels', 'image_label_selector');
        $this->_selected_labels = $selected_labels;
        $this->_image_dao = ImageDaoMysql::getInstance();
        $this->_context_id = $contextId;
    }

    public function getPanelContentTemplate(): string {
        return "system/image_label_selector.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $this->assignLabelSelector($data);
    }

    private function assignLabelSelector($data): void {
        $all_label_values = $this->labelsToArray($this->_image_dao->getAllLabels(), $this->_selected_labels);
        $image_label_values = $this->selectedLabelsToArray($this->_selected_labels);
        $data->assign('context_id', $this->_context_id);
        $data->assign("all_labels", $all_label_values);
        $data->assign("image_labels", $image_label_values);
    }

    private function labelsToArray(array $labels, array $image_labels): array {
        $label_values = array();
        foreach ($labels as $label) {
            $label_value = $this->createLabelValue($label);
            $label_value["is_selected"] = in_array($label, $image_labels);
            $label_values[] = $label_value;
        }
        return $label_values;
    }

    private function selectedLabelsToArray(array $labels): array {
        $label_values = array();
        foreach ($labels as $label) {
            $label_value = $this->createLabelValue($label);
            $label_value["delete_checkbox"] = $this->renderSelectedLabelCheckbox($label);
            $label_values[] = $label_value;
        }
        return $label_values;
    }

    private function renderSelectedLabelCheckbox(ImageLabel $label): string {
        $checkbox = new SingleCheckbox("label_" . $this->_context_id . "_" . $label->getId() . "_delete", "", 0, false, "");
        return $checkbox->render();
    }

    private function createLabelValue(ImageLabel $label): array {
        $label_value = array();
        $label_value["id"] = $label->getId();
        $label_value["name"] = $label->getName();
        return $label_value;
    }

}

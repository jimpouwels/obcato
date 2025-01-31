<?php

namespace Obcato\Core\view\views;

use Obcato\Core\database\dao\ImageDao;
use Obcato\Core\database\dao\ImageDaoMysql;
use Obcato\Core\modules\images\model\ImageLabel;
use Obcato\Core\view\TemplateData;

class ImageLabelSelector extends Panel {

    private array $selectedLabels;
    private ImageDao $imageDao;
    private int $contextId;
    private bool $includeNewLabelField;

    public function __construct(array $selectedLabels, int $contextId, bool $includeNewLabelField = false) {
        parent::__construct('Labels', 'image_label_selector');
        $this->selectedLabels = $selectedLabels;
        $this->imageDao = ImageDaoMysql::getInstance();
        $this->contextId = $contextId;
        $this->includeNewLabelField = $includeNewLabelField;
    }

    public function getPanelContentTemplate(): string {
        return "system/image_label_selector.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $allLabelValues = $this->labelsToArray($this->imageDao->getAllLabels(), $this->selectedLabels);
        $imageLabelValues = $this->selectedLabelsToArray($this->selectedLabels);
        $data->assign('context_id', $this->contextId);
        if ($this->includeNewLabelField) {
            $newLabelField = new TextField("new_image_label_" . $this->contextId, $this->getTextResource("new_image_label_field_label"), "", false, false, false);
            $data->assign('new_image_label_field', $newLabelField->render());
        }
        $data->assign("all_labels", $allLabelValues);
        $data->assign("image_labels", $imageLabelValues);
    }

    private function labelsToArray(array $labels, array $imageLabels): array {
        $labelValues = array();
        foreach ($labels as $label) {
            $labelValue = $this->createLabelValue($label);
            $labelValue["is_selected"] = in_array($label, $imageLabels);
            $labelValues[] = $labelValue;
        }
        return $labelValues;
    }

    private function selectedLabelsToArray(array $labels): array {
        $labelValues = array();
        foreach ($labels as $label) {
            $labelValue = $this->createLabelValue($label);
            $labelValue["delete_checkbox"] = $this->renderSelectedLabelCheckbox($label);
            $labelValues[] = $labelValue;
        }
        return $labelValues;
    }

    private function renderSelectedLabelCheckbox(ImageLabel $label): string {
        $checkbox = new SingleCheckbox("label_" . $this->contextId . "_" . $label->getId() . "_delete", "", 0, false, "");
        return $checkbox->render();
    }

    private function createLabelValue(ImageLabel $label): array {
        $labelValue = array();
        $labelValue["id"] = $label->getId();
        $labelValue["name"] = $label->getName();
        return $labelValue;
    }

}

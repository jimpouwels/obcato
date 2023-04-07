<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "database/dao/image_dao.php";
    require_once CMS_ROOT . "view/views/panel.php";

    class ImageLabelSelector extends Panel {

        private static $TEMPLATE = "system/image_label_selector.tpl";
        private $_template_engine;
        private $_selected_labels;
        private $_image_dao;
        private $_context_id;

        public function __construct($selected_labels, $context_id) {
            parent::__construct('Labels', 'image_label_selector');
            $this->_template_engine = TemplateEngine::getInstance();
            $this->_selected_labels = $selected_labels;
            $this->_image_dao = ImageDao::getInstance();
            $this->_context_id = $context_id;
        }

        public function renderVisual(): string {
            return parent::renderVisual();
        }

        public function renderPanelContent() {
            $this->assignLabelSelector();
            return $this->_template_engine->fetch(self::$TEMPLATE);
        }

        private function assignLabelSelector() {
            $all_label_values = $this->labelsToArray($this->_image_dao->getAllLabels(), $this->_selected_labels);
            $image_label_values = $this->selectedLabelsToArray($this->_selected_labels);
            $this->_template_engine->assign('context_id', $this->_context_id);
            $this->_template_engine->assign("all_labels", $all_label_values);
            $this->_template_engine->assign("image_labels", $image_label_values);
        }

        private function labelsToArray($labels, $image_labels) {
            $label_values = array();
            foreach ($labels as $label) {
                $label_value = $this->createLabelValue($label);
                $label_value["is_selected"] = in_array($label, $image_labels);
                $label_values[] = $label_value;
            }
            return $label_values;
        }

        private function selectedLabelsToArray($labels) {
            $label_values = array();
            foreach ($labels as $label) {
                $label_value = $this->createLabelValue($label);
                $label_value["delete_checkbox"] = $this->renderSelectedLabelCheckbox($label);
                $label_values[] = $label_value;
            }
            return $label_values;
        }

        private function renderSelectedLabelCheckbox($label) {
            $checkbox = new SingleCheckbox("label_" . $this->_context_id . "_" . $label->getId() . "_delete", "", 0, false, "");
            return $checkbox->render();
        }

        private function createLabelValue($label) {
            $label_value = array();
            $label_value["id"] = $label->getId();
            $label_value["name"] = $label->getName();
            return $label_value;
        }

    }

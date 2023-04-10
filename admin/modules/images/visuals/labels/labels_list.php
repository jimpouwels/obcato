<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/visual.php";
    require_once CMS_ROOT . "view/views/information_message.php";
    require_once CMS_ROOT . "view/views/form_checkbox_single.php";
    require_once CMS_ROOT . "database/dao/image_dao.php";

    class LabelsList extends Panel {

        private static $TEMPLATE = "images/labels/list.tpl";

        private $_image_dao;

        public function __construct() {
            parent::__construct('Labels');
            $this->_image_dao = ImageDao::getInstance();
        }

        public function render(): string {
            return parent::render();
        }

        public function renderPanelContent() {
            $this->getTemplateEngine()->assign("all_labels", $this->getAllLabels());
            $this->getTemplateEngine()->assign("no_labels_message", $this->getNoLabelsMessage());
            return $this->getTemplateEngine()->fetch("modules/" . self::$TEMPLATE);
        }

        private function getAllLabels() {
            $label_values = array();
            $all_labels = $this->_image_dao->getAllLabels();
            foreach ($all_labels as $label) {
                $label_values[] = $this->createLabelValue($label);
            }
            return $label_values;
        }

        private function getNoLabelsMessage() {
            $message = new InformationMessage("Geen labels gevonden");
            return $message->render();
        }

        private function createLabelValue($label) {
            $label_value = array();
            $label_value["id"] = $label->getId();
            $label_value["name"] = $label->getName();
            $label_value["delete_checkbox"] = $this->getDeleteCheckBox($label);
            return $label_value;
        }

        private function getDeleteCheckBox($label) {
            $checkbox = new SingleCheckBox("label_" . $label->getId() . "_delete", "", 0, false, "");
            return $checkbox->render();
        }
    }

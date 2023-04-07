<?php
    defined('_ACCESS') or die;

    class ImageSearch extends Panel {

        private static $TEMPLATE = "images/images/search.tpl";

        private $_image_dao;
        private $_images_pre_handler;

        public function __construct($images_pre_handler) {
            parent::__construct('Zoeken', 'image_search');
            $this->_image_dao = ImageDao::getInstance();
            $this->_images_pre_handler = $images_pre_handler;
        }

        public function renderVisual(): string {
            return parent::renderVisual();
        }

        public function renderPanelContent() {
            $this->getTemplateEngine()->assign("title_search_field", $this->getTitleSearchField()->render());
            $this->getTemplateEngine()->assign("filename_search_field", $this->getFileNameSearchField()->render());
            $this->getTemplateEngine()->assign("labels_search_field", $this->getLabelPullDown()->render());
            $this->getTemplateEngine()->assign("search_button", $this->getSearchButton()->render());

            return $this->getTemplateEngine()->fetch("modules/" . self::$TEMPLATE);
        }

        private function getTitleSearchField() {
            $title_search_field = new TextField("s_title", "Titel", $this->_images_pre_handler->getCurrentSearchTitleFromGetRequest(), false, false, null);
            return $title_search_field;
        }

        private function getFileNameSearchField() {
            $filename_search_field = new TextField("s_filename", "Bestandsnaam", $this->_images_pre_handler->getCurrentSearchFilenameFromGetRequest(), false, false, null);
            return $filename_search_field;
        }

        private function getLabelPullDown() {
            $labels = $this->getLabels();
            $currently_selected_label = $this->_images_pre_handler->getCurrentSearchLabelFromGetRequest();
            $labels_pulldown = new PullDown("s_label", "Label", (is_null($currently_selected_label) ? null : $currently_selected_label), $labels, false, "");
            return $labels_pulldown;
        }

        private function getSearchButton() {
            $search_button = new Button("", "Zoeken", "document.getElementById('image_search').submit(); return false;");
            return $search_button;
        }

        private function getLabels() {
            $labels_name_value_pair = array();
            $labels_name_value_pair[] = array('name' => '&gt; Selecteer', 'value' => null);
            foreach ($this->_image_dao->getAllLabels() as $label) {
                $labels_name_value_pair[] = array('name' => $label->getName(), 'value' => $label->getId());
            }
            return $labels_name_value_pair;
        }

    }

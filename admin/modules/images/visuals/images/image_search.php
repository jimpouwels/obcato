<?php
    defined('_ACCESS') or die;

    class ImageSearch extends Panel {


        private ImageDao $_image_dao;
        private ImageRequestHandler $_images_request_handler;

        public function __construct($images_request_handler) {
            parent::__construct('Zoeken', 'image_search');
            $this->_image_dao = ImageDao::getInstance();
            $this->_images_request_handler = $images_request_handler;
        }

        public function getPanelContentTemplate(): string {
            return "modules/images/images/search.tpl";
        }

        public function loadPanelContent(Smarty_Internal_Data $data): void {
            $data->assign("title_search_field", $this->getTitleSearchField()->render());
            $data->assign("filename_search_field", $this->getFileNameSearchField()->render());
            $data->assign("labels_search_field", $this->getLabelPullDown()->render());
            $data->assign("search_button", $this->getSearchButton()->render());
        }

        private function getTitleSearchField(): TextField {
            return new TextField("s_title", "Titel", $this->_images_request_handler->getCurrentSearchTitleFromGetRequest(), false, false, null);
        }

        private function getFileNameSearchField(): TextField {
            return new TextField("s_filename", "Bestandsnaam", $this->_images_request_handler->getCurrentSearchFilenameFromGetRequest(), false, false, null);
        }

        private function getLabelPullDown(): Pulldown {
            $labels = $this->getLabels();
            $currently_selected_label = $this->_images_request_handler->getCurrentSearchLabelFromGetRequest();
            return new PullDown("s_label", "Label", (is_null($currently_selected_label) ? null : $currently_selected_label), $labels, false, "");
        }

        private function getSearchButton(): Button {
            return new Button("", "Zoeken", "document.getElementById('image_search').submit(); return false;");
        }

        private function getLabels(): array {
            $labels_name_value_pair = array();
            $labels_name_value_pair[] = array('name' => '&gt; Selecteer', 'value' => null);
            foreach ($this->_image_dao->getAllLabels() as $label) {
                $labels_name_value_pair[] = array('name' => $label->getName(), 'value' => $label->getId());
            }
            return $labels_name_value_pair;
        }

    }

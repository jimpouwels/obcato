<?php
    
    defined("_ACCESS") or die;

    require_once CMS_ROOT . "request_handlers/element_form.php";

    class PhotoAlbumElementForm extends ElementForm {

        private PhotoAlbumElement $_photo_album_element;
        private array $_selected_labels;
        private array $_removed_labels;

        public function __construct(PhotoAlbumElement $photo_album_element) {
            parent::__construct($photo_album_element);
            $this->_photo_album_element = $photo_album_element;
        }

        public function loadFields(): void {
            $element_id = $this->_photo_album_element->getId();
            $title = $this->getFieldValue('element_' . $element_id . '_title');
            $number_of_results = $this->getNumber('element_' . $element_id . '_number_of_results', $this->getTextResource("form_invalid_number_error"));
            if ($this->hasErrors())
                throw new FormException();
            else {
                parent::loadFields();
                $this->_photo_album_element->setTitle($title);
                $this->_photo_album_element->setNumberOfResults($number_of_results);
            }

            $this->_selected_labels = $this->getFieldValues('select_labels_' . $this->_photo_album_element->getId());
            $this->_removed_labels = $this->getLabelsToDeleteFromPostRequest();
        }

        public function getSelectedLabels(): array {
            return $this->_selected_labels;
        }

        public function getLabelsToRemove(): array {
            return $this->_removed_labels;
        }

        private function getLabelsToDeleteFromPostRequest(): array {
            $labels_to_remove = array();
            $element_labels = $this->_photo_album_element->getLabels();
            foreach ($element_labels as $element_label) {
                if (isset($_POST['label_' . $this->_photo_album_element->getId() . '_' . $element_label->getId() . '_delete'])) {
                    $labels_to_remove[] = $element_label;
                }
            }
            return $labels_to_remove;
        }

    }
<?php
    
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "request_handlers/http_request_handler.php";
    require_once CMS_ROOT . "elements/photo_album_element/photo_album_element_form.php";
    require_once CMS_ROOT . "database/dao/element_dao.php";
    require_once CMS_ROOT . "database/dao/image_dao.php";
    require_once CMS_ROOT . "elements/element_contains_errors_exception.php";

    class PhotoAlbumElementRequestHandler extends HttpRequestHandler {

        private PhotoAlbumElement $_photo_album_element;
        private ElementDao $_element_dao;
        private ImageDao $_image_dao;
        private PhotoAlbumElementForm $_photo_album_element_form;

        public function __construct(PhotoAlbumElement $photo_album_element) {
            $this->_photo_album_element = $photo_album_element;
            $this->_element_dao = ElementDao::getInstance();
            $this->_image_dao = ImageDao::getInstance();
            $this->_photo_album_element_form = new PhotoAlbumElementForm($photo_album_element);
        }

        public function handleGet(): void {
        }

        public function handlePost(): void {
            try {
                $this->_photo_album_element_form->loadFields();
                $this->removeSelectedLabels();
                $this->addSelectedLabels();
                $this->_element_dao->updateElement($this->_photo_album_element);
            } catch (FormException $e) {
                throw new ElementContainsErrorsException("Photo album element contains errors");
            }
        }

        private function addSelectedLabels(): void {
            $selected_labels = $this->_photo_album_element_form->getSelectedLabels();
            if ($selected_labels) {
                foreach ($selected_labels as $selected_label_id) {
                    $label = $this->_image_dao->getLabel($selected_label_id);
                    $this->_photo_album_element->addLabel($label);
                }
            }
        }

        private function removeSelectedLabels(): void {
            foreach ($this->_photo_album_element_form->getLabelsToRemove() as $label) {
                $this->_photo_album_element->removeLabel($label);
            }
        }
    }
?>
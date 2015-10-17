<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/element.php";
    require_once CMS_ROOT . "database/mysql_connector.php";
    require_once CMS_ROOT . "database/dao/image_dao.php";
    require_once CMS_ROOT . "elements/image_element/visuals/image_element_statics.php";
    require_once CMS_ROOT . "elements/image_element/visuals/image_element_editor.php";
    require_once CMS_ROOT . "elements/image_element/image_element_request_handler.php";
    require_once CMS_ROOT . "frontend/image_element_visual.php";

    class ImageElement extends Element {

        private $_title;
        private $_alternative_text;
        private $_align;
        private $_int;
        private $_height;
        private $_width;
        private $_image_id;
        private $_metadata_provider;

        public function __construct() {
            $this->_metadata_provider = new ImageElementMetaDataProvider();
        }

        public function setTitle($title) {
            $this->_title = $title;
        }

        public function getTitle() {
            return $this->_title;
        }

        public function setAlternativeText($alternative_text) {
            $this->_alternative_text = $alternative_text;
        }

        public function getAlternativeText() {
            return $this->_alternative_text;
        }

        public function setAlign($align) {
            $this->_align = $align;
        }

        public function getAlign() {
            return $this->_align;
        }

        public function getWidth() {
            return $this->_width;
        }

        public function setWidth($width) {
            $this->_width = $width;
        }

        public function getHeight() {
            return $this->_height;
        }

        public function setHeight($height) {
            $this->_height = $height;
        }

        public function setImageId($image_id) {
            $this->_image_id = $image_id;
        }

        public function getImageId() {
            return $this->_image_id;
        }

        public function getImage() {
            $image = null;
            if ($this->_image_id != null) {
                $image_dao = ImageDao::getInstance();
                $image = $image_dao->getImage($this->_image_id);
            }
            return $image;
        }

        public function getStatics() {
            return new ImageElementStatics();
        }

        public function getBackendVisual() {
            return new ImageElementEditorVisual($this);
        }

        public function getFrontendVisual($current_page) {
            return new ImageElementFrontendVisual($current_page, $this);
        }

        public function initializeMetaData() {
            $this->_metadata_provider->getMetaData($this);
        }

        public function updateMetaData() {
            $this->_metadata_provider->updateMetaData($this);
        }

        public function getRequestHandler() {
            return new ImageElementRequestHandler($this);
        }
    }

    class ImageElementMetaDataProvider {

        public function getMetaData($element) {
            $mysql_database = MysqlConnector::getInstance();

            $query = "SELECT title, image_id, align, alternative_text, width, height FROM image_elements_metadata WHERE element_id = " . $element->getId();
            $result = $mysql_database->executeQuery($query);
            while ($row = $result->fetch_assoc()) {
                $element->setTitle($row['title']);
                $element->setAlternativeText($row['alternative_text']);
                $element->setAlign($row['align']);
                $element->setImageId($row['image_id']);
                $element->setWidth($row['width']);
                $element->setHeight($row['height']);
            }
        }

        public function updateMetaData($element) {
            $mysql_database = MysqlConnector::getInstance();
            if ($this->persisted($element)) {
                $image_id = "NULL";
                if ($element->getImageId() != '' && !is_null($element->getImageId())) {
                    $image_id = $element->getImageId();
                }
                $query = "UPDATE image_elements_metadata SET title = '" . $element->getTitle() . "', alternative_text = '"
                           . $element->getAlternativeText() . "', align = '" . $element->getAlign() . "', image_id = "
                           . $image_id . ", width = " . $element->getWidth() . ", height = " . $element->getHeight() . ""
                           . " WHERE element_id = " . $element->getId();
            } else {
                $query = "INSERT INTO image_elements_metadata (title, alternative_text, align, width, height, image_id, element_id) VALUES "
                          . "('" . $element->getTitle() . "', '" . $element->getAlternativeText() . "', '" . $element->getAlign() . "', 0 , 0"
                          . ", NULL, " . $element->getId() . ")";
            }
            $mysql_database->executeQuery($query);
        }

        private function persisted($element) {
            $mysql_database = MysqlConnector::getInstance();
            $query = "SELECT t.id, e.id FROM image_elements_metadata t, elements e WHERE t.element_id = " . $element->getId() . "
                      AND e.id = " . $element->getId();
            $result = $mysql_database->executeQuery($query);
            while ($row = $result->fetch_assoc()) {
                return true;
            }
            return false;
        }

    }

?>

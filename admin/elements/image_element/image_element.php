<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/element.php";
    require_once CMS_ROOT . "core/model/element_metadata_provider.php";
    require_once CMS_ROOT . "database/mysql_connector.php";
    require_once CMS_ROOT . "database/dao/image_dao.php";
    require_once CMS_ROOT . "elements/image_element/visuals/image_element_statics.php";
    require_once CMS_ROOT . "elements/image_element/visuals/image_element_editor.php";
    require_once CMS_ROOT . "elements/image_element/image_element_request_handler.php";
    require_once CMS_ROOT . "frontend/image_element_visual.php";

    class ImageElement extends Element {

        private ?string $_alternative_text = null;
        private ?string $_align = null;
        private ?int $_height = null;
        private ?int $_width = null;
        private ?int $_image_id = null;

        public function __construct() {
            parent::__construct(new ImageElementMetadataProvider($this));
        }

        public function setAlternativeText(?string $alternative_text): void {
            $this->_alternative_text = $alternative_text;
        }

        public function getAlternativeText(): ?string {
            return $this->_alternative_text;
        }

        public function setAlign(?string $align): void {
            $this->_align = $align;
        }

        public function getAlign(): ?string {
            return $this->_align;
        }

        public function getWidth(): ?int {
            return $this->_width;
        }

        public function setWidth(?int $width): void {
            $this->_width = $width;
        }

        public function getHeight(): ?int {
            return $this->_height;
        }

        public function setHeight(?int $height): void {
            $this->_height = $height;
        }

        public function setImageId(?int $image_id): void {
            $this->_image_id = $image_id;
        }

        public function getImageId(): ?int {
            return $this->_image_id;
        }

        public function getImage(): ?Image {
            $image = null;
            if ($this->_image_id != null) {
                $image_dao = ImageDao::getInstance();
                $image = $image_dao->getImage($this->_image_id);
            }
            return $image;
        }

        public function getStatics(): Visual {
            return new ImageElementStatics();
        }

        public function getBackendVisual(): ElementVisual {
            return new ImageElementEditorVisual($this);
        }

        public function getFrontendVisual(Page $current_page): ImageElementFrontendVisual {
            return new ImageElementFrontendVisual($current_page, $this);
        }

        public function getRequestHandler(): HttpRequestHandler {
            return new ImageElementRequestHandler($this);
        }

        public function getSummaryText(): string {
            $summary_text = $this->getTitle();
            $image = $this->getImage();
            if ($image) {
                $summary_text .= ': ' . $image->getTitle() . ' (' . $image->getFileName() . ')';
            }
            return $summary_text;            
        }
    }

    class ImageElementMetadataProvider extends ElementMetadataProvider {
        
        public function __construct(Element $element) {
            parent::__construct($element);
        }
        
        public function getTableName(): string {
            return "image_elements_metadata";
        }

        public function constructMetaData(array $row, $element): void {
            $element->setTitle($row['title']);
            $element->setAlternativeText($row['alternative_text']);
            $element->setAlign($row['align']);
            $element->setImageId($row['image_id']);
            $element->setWidth($row['width']);
            $element->setHeight($row['height']);
        }

        public function updateMetaData(Element $element): void {
            $mysql_database = MysqlConnector::getInstance();
            if ($this->isPersisted($element)) {
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

        private function isPersisted(Element $element): bool {
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

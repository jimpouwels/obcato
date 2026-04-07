<?php

namespace Obcato\Core\view\views;

use Obcato\Core\database\dao\ImageDao;
use Obcato\Core\database\dao\ImageDaoMysql;
use Obcato\Core\view\TemplateEngine;

class ImagePicker extends ObjectPicker {

    private ImageDao $imageDao;

    public function __construct(string $fieldName, string $label, ?string $value, string $opener_click_id) {
        parent::__construct($fieldName, $label, $value, $opener_click_id);
        $this->imageDao = ImageDaoMysql::getInstance();
    }

    public function getType(): string {
        return Search::$IMAGES_POPUP_TYPE;
    }

    protected function shouldShowButton(): bool {
        // Only show button if no image is selected
        return empty($this->getValue());
    }

    protected function renderEnhancedDisplay(): string {
        $imageId = $this->getValue();
        if (empty($imageId)) {
            return "";
        }

        $image = $this->imageDao->getImage((int)$imageId);
        if (!$image) {
            error_log("ImagePicker: Could not load image with ID: " . $imageId);
            return "";
        }

        $templateEngine = TemplateEngine::getInstance();
        $templateEngine->assign("image_id", $imageId);
        $templateEngine->assign("image_title", $image->getTitle());
        $templateEngine->assign("field_name", $this->getFieldName());
        $templateEngine->assign("delete_field_name", "delete_" . $this->getFieldName());
        $templateEngine->assign("image_url", $this->getImageBaseUrl() . "/" . $imageId);
        $templateEngine->assign("delete_icon_url", $this->getBackendBaseUrlRaw() . "?file=/default/img/default_icons/delete_small.png");
        
        return $templateEngine->fetch("image_picker_enhanced.tpl");
    }

}
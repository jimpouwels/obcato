<?php

namespace Obcato\Core\modules\images;

use Obcato\Core\authentication\Session;
use Obcato\Core\core\form\FormException;
use Obcato\Core\database\dao\ImageDao;
use Obcato\Core\database\dao\ImageDaoMysql;
use Obcato\Core\modules\images\model\Image;
use Obcato\Core\request_handlers\HttpRequestHandler;
use Obcato\Core\templates\elements\ImageForm;
use Obcato\Core\utilities\FileUtility;
use const Obcato\Core\UPLOAD_DIR;

class ImageRequestHandler extends HttpRequestHandler {

    private static string $IMAGE_QUERYSTRING_KEY = "image";
    private static string $TITLE_SEARCH_QUERYSTRING_KEY = "s_title";
    private static string $FILENAME_SEARCH_QUERYSTRING_KEY = "s_filename";
    private static string $LABEL_SEARCH_QUERYSTRING_KEY = "s_label";

    private ImageDao $imageDao;
    private ?Image $currentImage = null;

    public function __construct() {
        $this->imageDao = ImageDaoMysql::getInstance();
    }

    public function handleGet(): void {
        $this->currentImage = $this->getImageFromGetRequest();
    }

    public function handlePost(): void {
        $this->currentImage = $this->imageDao->getImage($this->getImageIdFromPostRequest());
        if ($this->isUpdateImageAction()) {
            $this->updateImage();
        } else if ($this->isDeleteImageAction()) {
            $this->deleteImage();
        } else if ($this->isAddImageAction()) {
            $this->addImage();
        } else if ($this->isToggleImagePublishedAction()) {
            $this->toggleImagePublished();
        }
    }

    public function getCurrentImage(): ?Image {
        return $this->currentImage;
    }

    public function getCurrentSearchTitleFromGetRequest(): ?string {
        return $this->getQueryStringValueFromGetRequest(self::$TITLE_SEARCH_QUERYSTRING_KEY);
    }

    public function getCurrentSearchFilenameFromGetRequest(): ?string {
        return $this->getQueryStringValueFromGetRequest(self::$FILENAME_SEARCH_QUERYSTRING_KEY);
    }

    public function getCurrentSearchLabelFromGetRequest(): ?string {
        return $this->getQueryStringValueFromGetRequest(self::$LABEL_SEARCH_QUERYSTRING_KEY);
    }

    private function updateImage(): void {
        try {
            $imageForm = new ImageForm($this->currentImage);
            $imageForm->loadFields();
            $this->addNewlySelectedLabelsToImage($imageForm->getSelectedLabels());
            $this->deleteSelectedLabelsFromImage();
            $this->saveUploadedImage();
            if (!empty($imageForm->getNewImageLabelName())) {
                $label = $this->imageDao->getLabelByName($imageForm->getNewImageLabelName());
                if (!$label) {
                    $label = $this->imageDao->createLabel($imageForm->getNewImageLabelName());
                }
                if (!$this->hasLabel($this->currentImage, $label->getId())) {
                    $this->imageDao->addLabelToImage($label->getId(), $this->currentImage);
                }
            }
            $this->imageDao->updateImage($this->currentImage);
            $this->sendSuccessMessage($this->getTextResource("images_save_success_message"));
        } catch (FormException $e) {
            $this->sendErrorMessage($this->getTextResource("images_save_failed_message"));
        }
    }

    private function hasLabel(Image $image, int $labelId): bool {
        foreach ($this->imageDao->getLabelsForImage($image->getId()) as $label) {
            if ($label->getId() === $labelId) {
                return true;
            }
        }
        return false;
    }

    private function toggleImagePublished(): void {
        try {
            $imageListForm = new ImageListForm();
            $imageListForm->loadFields();
            $imageToToggle = $this->imageDao->getImage($imageListForm->getImageId());
            $imageToToggle->setPublished(!$imageToToggle->isPublished());
            $this->imageDao->updateImage(($imageToToggle));
            $successMessageTextResourceId = "image_successfully_depublished";
            if ($imageToToggle->isPublished()) {
                $successMessageTextResourceId = "image_successfully_published";
            }
            $this->sendSuccessMessage(Session::getTextResource($successMessageTextResourceId));
            $this->redirectTo($this->getBackendBaseUrl());
        } catch (FormException $e) {
            $this->sendErrorMessage("Afbeelding niet worden ge(de)publiseerd");
        }
    }

    private function deleteImage(): void {
        $this->imageDao->deleteImage($this->currentImage);
        $this->sendSuccessMessage("Afbeelding succesvol verwijderd");
        $this->redirectTo($this->getBackendBaseUrl());
    }

    private function addImage(): void {
        $newImage = $this->imageDao->createImage();
        $this->sendSuccessMessage("Afbeelding succesvol aangemaakt");
        $this->redirectTo($this->getBackendBaseUrl() . "&image=" . $newImage->getId());
    }

    private function getImageFromGetRequest(): ?Image {
        $currentImage = null;
        if (isset($_GET[self::$IMAGE_QUERYSTRING_KEY]) && $_GET[self::$IMAGE_QUERYSTRING_KEY] != "") {
            $currentImage = $this->imageDao->getImage($_GET[self::$IMAGE_QUERYSTRING_KEY]);
        }
        return $currentImage;
    }

    private function addNewlySelectedLabelsToImage(array $selectedLabels): void {
        if (count($selectedLabels) == 0) {
            return;
        }
        $existingLabels = $this->imageDao->getLabelsForImage($this->currentImage->getId());
        foreach ($selectedLabels as $selectedLabelId) {
            if (!$this->isLabelAlreadyAdded($selectedLabelId, $existingLabels)) {
                $this->imageDao->addLabelToImage($selectedLabelId, $this->currentImage);
            }
        }
    }

    private function isLabelAlreadyAdded(int $selectedLabelId, array $existingLabels): bool {
        foreach ($existingLabels as $existingLabel) {
            if ($selectedLabelId == $existingLabel->getId()) {
                return true;
            }
        }
        return false;
    }

    private function deleteSelectedLabelsFromImage(): void {
        $imageLabels = $this->imageDao->getLabelsForImage($this->currentImage->getId());
        foreach ($imageLabels as $imageLabel) {
            if (isset($_POST["label_" . $this->currentImage->getId() . "_" . $imageLabel->getId() . "_delete"])) {
                $this->imageDao->deleteLabelForImage($imageLabel->getId(), $this->currentImage);
            }
        }
    }

    private function saveUploadedImage(): void {
        $newFilename = $this->getNewImageFilename();
        if (is_uploaded_file($_FILES["image_file"]["tmp_name"])) {
            $this->deletePreviousImage();
            $this->moveImageToUploadDirectory($newFilename);
            $this->saveThumbnailForUploadedImage($newFilename);
        }
    }

    private function getNewImageFilename(): string {
        $currentImageId = $this->currentImage->getId();
        $uploadedImageFilename = $_FILES["image_file"]["name"];
        return "UPLIMG-00$currentImageId" . "_$uploadedImageFilename";
    }

    private function saveThumbnailForUploadedImage(string $newFilename): void {
        $thumbFilename = "THUMB-" . $newFilename;
        FileUtility::saveThumb($newFilename, UPLOAD_DIR, $thumbFilename, 50, 50);
        $this->currentImage->setThumbFileName($thumbFilename);
    }

    private function moveImageToUploadDirectory(string $newFilename): void {
        rename($_FILES["image_file"]["tmp_name"], UPLOAD_DIR . "/" . $newFilename);
        $this->currentImage->setFilename($newFilename);
    }

    private function deletePreviousImage(): void {
        FileUtility::deleteImage($this->currentImage, UPLOAD_DIR);
    }

    private function getImageIdFromPostRequest(): ?int {
        $imageId = null;
        if (isset($_POST["image_id"]) && $_POST["image_id"]) {
            $imageId = $_POST["image_id"];
        }
        return $imageId;
    }

    private function isAddImageAction(): bool {
        return isset($_POST["add_image_action"]) && $_POST["add_image_action"] == "add_image";
    }

    private function isToggleImagePublishedAction(): bool {
        return $this->isAction("toggle_image_published");
    }

    private function isDeleteImageAction(): bool {
        return $this->isAction("delete_image") && isset($_POST["image_id"]);
    }

    private function isUpdateImageAction(): bool {
        return $this->isAction("update_image") && isset($_POST["image_id"]);
    }

    private function isAction(string $name): bool {
        return isset($_POST['action']) && $_POST["action"] == $name && isset($_POST["image_id"]);
    }

    private function getQueryStringValueFromGetRequest($queryStringKey): ?string {
        $value = null;
        if (isset($_GET[$queryStringKey]) && $_GET[$queryStringKey] != '') {
            $value = $_GET[$queryStringKey];
        }
        return $value;
    }

}

?>
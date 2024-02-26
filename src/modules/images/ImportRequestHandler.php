<?php

namespace Obcato\Core\modules\images;

use FileUtility;
use Obcato\Core\database\dao\ImageDao;
use Obcato\Core\database\dao\ImageDaoMysql;
use Obcato\Core\request_handlers\HttpRequestHandler;
use ZipArchive;

class ImportRequestHandler extends HttpRequestHandler {
    private static string $ZIP_FILE_ID = "import_zip_file";
    private ImageDao $imageDao;

    public function __construct() {
        $this->imageDao = ImageDaoMysql::getInstance();
    }

    public function handleGet(): void {}

    public function handlePost(): void {
        if (isset($_FILES[self::$ZIP_FILE_ID]) && is_uploaded_file($_FILES[self::$ZIP_FILE_ID]["tmp_name"])) {
            $importCount = 0;
            $zip = new ZipArchive();
            $zip->open($_FILES["import_zip_file"]["tmp_name"]);
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $fileEntryName = $zip->getNameIndex($i);
                if (str_starts_with($fileEntryName, '__MACOSX/')) {
                    continue;
                }
                $splits = explode(".", $fileEntryName);
                $extension = $splits[count($splits) - 1];
                $extension = strtolower($extension);
                if ($extension == "jpeg" || $extension == "jpg" || $extension == "gif" || $extension == "png") {
                    $newImage = $this->imageDao->createImage();
                    $newImage->setTitle($fileEntryName);
                    $newImage->setPublished(1);
                    $newFilename = "UPLIMG-00" . $newImage->getId() . "00" . $fileEntryName;
                    $newFile = fopen(UPLOAD_DIR . "/" . $newFilename, "w");
                    fwrite($newFile, $zip->getFromIndex($i));
                    fclose($newFile);
                    $newImage->setFilename($newFilename);
                    $thumbFilename = "THUMB-" . $newFilename;
                    FileUtility::saveThumb($newFilename, UPLOAD_DIR, $thumbFilename, 50, 50);
                    $newImage->setThumbFileName($thumbFilename);
                    if (isset($_POST["import_label"]) && $_POST["import_label"] != "") {
                        $this->imageDao->addLabelToImage($_POST["import_label"], $newImage);
                    }
                    $this->imageDao->updateImage($newImage);
                    $importCount += 1;
                }
            }
            $zip->close();
            if ($importCount == 0) {
                $this->sendErrorMessage("Geen afbeeldingen gevonden in ZIP bestand");
            } else {
                $this->sendSuccessMessage($importCount . " afbeeldingen geimporteerd");
            }
        }
    }
}
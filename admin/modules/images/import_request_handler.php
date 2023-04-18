<?php
    defined("_ACCESS") or die;

    require_once CMS_ROOT . "database/dao/image_dao.php";

    class ImportRequestHandler extends HttpRequestHandler {
        private static string $ZIP_FILE_ID = "import_zip_file";
        private ImageDao $_image_dao;

        public function __construct() {
            $this->_image_dao = ImageDao::getInstance();
        }

        public function handleGet(): void {                
        }

        public function handlePost(): void {
            if (isset($_FILES[self::$ZIP_FILE_ID]) && is_uploaded_file($_FILES[self::$ZIP_FILE_ID]["tmp_name"])) {
                $number_imported = 0;
                $zip = new ZipArchive();
                $zip->open($_FILES["import_zip_file"]["tmp_name"]);
                if ($zip) {
                    for ($i = 0; $i < $zip->numFiles; $i++) {
                        $file_entry_name = $zip->getNameIndex($i);
                        if (str_starts_with($file_entry_name, '__MACOSX/')) {
                            continue;
                        }
                        $splits = explode(".", $file_entry_name);
                        $extension = $splits[count($splits) - 1];
                        $extension = strtolower($extension);
                        if ($extension == "jpeg" || $extension == "jpg" || $extension == "gif" || $extension == "png") {
                            $new_image = $this->_image_dao->createImage();
                            $new_image->setTitle($file_entry_name);
                            $new_image->setPublished(1);
                            $new_file_name = "UPLIMG-00" . $new_image->getId() . "00" . $file_entry_name;
                            $file_contents = $zip->getFromIndex($i);
                            $new_file = fopen(UPLOAD_DIR . "/" . $new_file_name, "w");
                            fwrite($new_file, $file_contents);
                            fclose($new_file);
                            $new_image->setFileName($new_file_name);
                            $thumb_file_name = "THUMB-" . $new_file_name;
                            FileUtility::saveThumb($new_file_name, UPLOAD_DIR, $thumb_file_name, 50, 50);
                            $new_image->setThumbFileName($thumb_file_name);
                            if (isset($_POST["import_label"]) && $_POST["import_label"] != "") {
                                $this->_image_dao->addLabelToImage($_POST["import_label"], $new_image);
                            }
                            $this->_image_dao->updateImage($new_image);
                            $number_imported += 1;
                        }
                    }
                }
                $zip->close();
                if ($number_imported == 0) {
                    $this->sendErrorMessage("Geen afbeeldingen gevonden in ZIP bestand");
                } else {
                    $this->sendSuccessMessage($number_imported . " afbeeldingen geimporteerd");
                }
            }
        }
    }
?>
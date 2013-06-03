<?php

	// No direct access
	defined('_ACCESS') or die;
	
	include_once "dao/image_dao.php";
	include_once "dao/settings_dao.php";
	include_once "libraries/validators/form_validator.php";
	include_once "libraries/handlers/form_handler.php";
	include_once "libraries/system/notifications.php";
	include_once "libraries/utilities/file_utility.php";
	
	// handle post requests
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (isset($_POST['action'])) {
			switch ($_POST['action']) {
				case 'update_label':
					updateLabel();
					break;
			}
		}
		if (isset($_POST['label_delete_action']) && $_POST['label_delete_action'] == 'delete_labels') {
			deleteLabels();
		}
	}
	
	// label is being updated
	
	
	// labels must be deleted
	
	
	// =================================== IMPORT ============================================================
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (isset($_FILES['import_zip_file']) && is_uploaded_file($_FILES['import_zip_file']['tmp_name'])) {
			$number_imported = 0;
			$zip = zip_open($_FILES['import_zip_file']['tmp_name']);
			if ($zip) {
				$image_dao = ImageDao::getInstance();
				$upload_dir = Settings::find()->getUploadDir();
			
				while ($zip_entry = zip_read($zip)) {					
					$file_entry_name = zip_entry_name($zip_entry);
					$splits = explode('.', $file_entry_name);
					$extension = $splits[count($splits) - 1];
					
					if ($extension == 'JPEG' || $extension == 'jpeg' || $extension == 'JPG' || $extension == 'jpg' 
						|| $extension == 'GIF' || $extension == 'gif' || $extension == 'PNG' || $extension == 'png') {
						$new_image = NULL;
						$new_image = $image_dao->createImage();
						$new_image->setTitle($file_entry_name);
						$new_image->setPublished(0);
						$new_file_name = "UPLIMG-00" . $new_image->getId() . '00' . $file_entry_name;
						
						$zip_filesize = zip_entry_filesize($zip_entry);
						
						if (empty($zip_filesize)) continue;

						$file_contents = zip_entry_read($zip_entry, $zip_filesize);

						$new_file = fopen($upload_dir . "/" . $new_file_name, "w");
						fwrite($new_file, $file_contents);
						fclose($new_file);
						zip_entry_close($zip_entry);
					
						$new_image->setFileName($new_file_name);				
						$thumb_file_name = "THUMB-" . $new_file_name;
					
						FileUtility::saveThumb($new_file_name, $upload_dir, $thumb_file_name, 50, 50);
					
						$new_image->setThumbFileName($thumb_file_name);
						
						// save the label
						if (isset($_POST['import_label']) && $_POST['import_label'] != '') {
							$image_dao->addLabelToImage($_POST['import_label'], $new_image);
						}
						
						$image_dao->updateImage($new_image);
						
						$number_imported += 1;
					}
				}
			}
			zip_close($zip);
			
			if ($number_imported == 0) {
				Notifications::setFailedMessage("Geen afbeeldingen gevonden in ZIP bestand");
			} else {
				Notifications::setSuccessMessage($number_imported . " afbeeldingen geimporteerd");
			}
		}
	}
?>
<?php

	
	defined('_ACCESS') or die;
	
	class FileUtility {
		
		/*
			Private constructor.
		*/
		private function __construct() {
		}
	
		/*
			Deletes all images that belong to the given image.
			
			@param $image The image to delete the files for
			@param $upload_dir The location of the files
		*/
		public static function deleteImage($image, $upload_dir) {
			// first delete the old file
			if (!is_null($image->getFileName()) && $image->getFileName() != '') {
				$old_file_name = $upload_dir . "/" . $image->getFileName();
				if (file_exists($old_file_name))
					unlink($old_file_name);
			}
			// now delete the thumb
			if (!is_null($image->getThumbFileName()) && $image->getThumbFileName() != '') {
				$old_file_name = $upload_dir . "/" . $image->getThumbFileName();
				if (file_exists($old_file_name))
					unlink($old_file_name);
			}
		}
		
		/*
			Saves a thumb nail for the given parameters.
			
			@param $source_image_filename The file name of the source image
			@param $directory The directory where the source image and the thumb will be placed in
			@param $thumb_file_name The name of the new thumbnail
			@param $thumb_width The width of the thumbnail to create
			@param $thumb_height The height of the thumbnail to create
		*/
		public static function saveThumb($source_image_filename, $directory, $thumb_file_name, $thumb_width, $thumb_height) {
			$splits = explode('.', $source_image_filename);
			$extension = $splits[count($splits) - 1];
			$target_image = imagecreatetruecolor($thumb_width, $thumb_height);
			if ($extension == 'jpeg' || $extension == 'jpg' || $extension == 'JPEG' || $extension == 'JPG') {
				$source_image = imagecreatefromjpeg($directory . "/" . $source_image_filename);
				imagecopyresized($target_image, $source_image, 0, 0, 0, 0, $thumb_width, $thumb_height, imagesx($source_image), imagesy($source_image));
				imagejpeg($target_image, $directory . "/" . $thumb_file_name);
			} else if ($extension == 'png' || $extension == 'PNG') {
				$source_image = imagecreatefrompng($directory . "/" . $source_image_filename);
				imagecopyresized($target_image, $source_image, 0, 0, 0, 0, $thumb_width, $thumb_height, imagesx($source_image), imagesy($source_image));
				imagepng($target_image, $directory . "/" . $thumb_file_name);
			} else if ($extension == 'gif' || $extension == 'GIF') {
				$source_image = imagecreatefromgif($directory . "/" . $source_image_filename);
				imagecopyresized($target_image, $source_image, 0, 0, 0, 0, $thumb_width, $thumb_height, imagesx($source_image), imagesy($source_image));
				imagegif($target_image, $directory . "/" . $thumb_file_name);
			}
		}
		
		/*
			Moves all contents from the given source directory to the given target directory.
			
			@param $source The souce folder
			@param $target The target folder
			@param $delete_source_dir True if the source directory should be deleted
		*/
		public static function moveDirectoryContents($source, $target, $delete_source_dir = false) {
			$files = scandir($source);
			foreach ($files as $file) {
				if ($file != "." && $file != "..") {
					$source_file = "$source/$file";
					$target_file = "$target/$file";
					if (!is_dir($source_file))
						copy($source_file, $target_file);
					else {
						if (!is_dir($target_file))
							mkdir($target_file);
						self::moveDirectoryContents($source_file, $target_file);
					}
				}
			}
			self::recursiveDelete($source);
			
			if ($delete_source_dir) {
				rmdir($source);
			}
		}
		
		/*
			Recursively deletes all contents of the given path.
			
			@param $path The path to delete all contents from
		*/
		public static function recursiveDelete($path, $remove_path_dir = false) {
			$files = scandir($path);
			// Cycle through all source files
			foreach ($files as $file) {
				if ($file != "." && $file != "..") {
					$source_file = "$path/$file";
					if (is_dir($source_file)) {
						self::recursiveDelete($source_file);
						rmdir($source_file);
					} else {
						unlink($source_file);
					}
				}
			}
			if (is_dir($path) && $remove_path_dir) {
				rmdir($path);
			}
		}
		
		/*
			Loads the contents of the given file.
			
			@param $path The path to the file
		*/
		public static function loadFileContents($path) {
			$file_contents = NULL;
			if (!is_dir($path)) {
				$file_contents = file_get_contents($path);
			}
			return $file_contents;
		}
		
	}
	
?>
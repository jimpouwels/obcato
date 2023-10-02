<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . '/utilities/string_utility.php';

class FileUtility {

    private function __construct() {}

    public static function deleteImage(Image $image, string $upload_dir): void {
        // first delete the old file
        if (!is_null($image->getFileName()) && $image->getFileName() != '') {
            $old_file_name = $upload_dir . "/" . $image->getFileName();
            if (file_exists($old_file_name)) {
                unlink($old_file_name);
            }
        }
        // now delete the thumb
        if (!is_null($image->getThumbFileName()) && $image->getThumbFileName() != '') {
            $old_file_name = $upload_dir . "/" . $image->getThumbFileName();
            if (file_exists($old_file_name)) {
                unlink($old_file_name);
            }
        }
    }

    public static function saveThumb(string $source_image_filename, string $directory, string $thumb_file_name, int $thumb_width, int $thumb_height): void {
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
    public static function moveDirectoryContents(string $source, string $target, bool $delete_source_dir = false): void {
        $files = scandir($source);
        foreach ($files as $file) {
            if ($file != "." && $file != "..") {
                $source_file = "$source/$file";
                $target_file = "$target/$file";
                if (!is_dir($source_file)) {
                    copy($source_file, $target_file);
                } else {
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
    public static function recursiveDelete(string $path, bool $remove_path_dir = false): void {
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

    public static function deleteFilesStartingWith(string $path, string $start_with_string): void {
        $files = scandir($path);
        foreach ($files as $file) {
            if (str_starts_with($file, $start_with_string)) {
                $source_file = "$path/$file";
                unlink($source_file);
            }
        }
    }

}

?>
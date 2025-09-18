<?php

namespace Obcato\Core\utilities;

use Obcato\Core\modules\images\model\Image;

class FileUtility {

    private function __construct() {}

    public static function deleteImage(Image $image, string $directory): void {
        // first delete the old file
        if ($image->getFilename()) {
            $oldFilePath = $directory . "/" . $image->getFilename();
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
        }
        // now delete the thumb
        if ($image->getThumbFileName()) {
            $oldFilePath = $directory . "/" . $image->getThumbFileName();
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
        }
    }

    public static function saveThumb(string $srcImage, string $directory, string $thumbFilename, int $thumbWidth, int $thumbHeight): void {
        $imageObj = imagecreatefromwebp($directory . '/' . $srcImage);
        $width = imagesx($imageObj);
        $height = imagesy($imageObj);
        $thumb = imagecreatetruecolor($width, $height);
        imagecopy($thumb, $imageObj,0,0,0,0, $width, $height);
        $thumbResized = imagescale($thumb, $thumbWidth, $thumbHeight);
        imagewebp($thumbResized, $directory . "/" . $thumbFilename, 80);
    }

    /*
        Moves all contents from the given source directory to the given target directory.

        @param $source The source folder
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
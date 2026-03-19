<?php

namespace Obcato\Core\utilities;

use Imagick;
use const Obcato\Core\UPLOAD_DIR;

class ImageUtility {

    private function __construct() {}

    public static function scaleX(Imagick $image, int $targetWidth): Imagick {
        $oldWidth = $image->getImageWidth();
        if ($oldWidth != $targetWidth) {
            $targetHeight = (int)($image->getImageHeight() * ($targetWidth / $oldWidth));
            $image->scaleImage($targetWidth, $targetHeight);
        }
        return $image;
    }

    public static function scaleY(Imagick $image, int $targetHeight): Imagick {
        $oldHeight = $image->getImageHeight();
        if ($oldHeight != $targetHeight) {
            $targetWidth = (int)($image->getImageWidth() * ($targetHeight / $oldHeight));
            $image->scaleImage($targetWidth, $targetHeight);
        }
        return $image;
    }

    public static function crop(Imagick $image, ?int $top, ?int $bottom, ?int $left, ?int $right): Imagick {
        $top = $top ?? 0;
        $bottom = $bottom ?? 0;
        $left = $left ?? 0;
        $right = $right ?? 0;
        $width = $image->getImageWidth() - $left - $right;
        $height = $image->getImageHeight() - $top - $bottom;
        $image->cropImage($width, $height, $left, $top);
        return $image;
    }

    public static function saveImageAsWebp(Imagick $image, string $filename): void {
        $image->setImageFormat('webp');
        $image->setImageCompressionQuality(90);
        $image->writeImage(UPLOAD_DIR . "/" . $filename);
    }

    public static function loadImage(string $filename): Imagick {
        $image = new Imagick();
        $image->readImage(UPLOAD_DIR . "/" . $filename);
        return $image;
    }

    public static function exists(?string $filename): bool {
        if (!$filename) {
            return false;
        }
        return file_exists(UPLOAD_DIR . "/" . $filename);
    }

    public static function delete(?string $filename): void {
        $filePath = UPLOAD_DIR . "/" . $filename;
        if ($filename && file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
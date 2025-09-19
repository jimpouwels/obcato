<?php

namespace Obcato\Core\utilities;

use GdImage;
use const Obcato\Core\UPLOAD_DIR;

class ImageUtility {

    private function __construct() {}

    public static function scaleX(GdImage $image, int $targetWidth) {
        $oldWidth = imagesx($image);
        if ($oldWidth != $targetWidth) {
            return imagescale($image, $targetWidth, imagesy($image) * ($targetWidth / $oldWidth));
        }
        return $image;
    }

    public static function scaleY(GdImage $image, int $targetHeight) {
        $oldHeight = imagesy($image);
        if ($oldHeight != $targetHeight) {
            return imagescale($image, imagesx($image) * ($targetHeight / $oldHeight), $targetHeight);
        }
        return $image;
    }

    public static function crop(GdImage $image, ?int $top, ?int $bottom, ?int $left, ?int $right) {
        $top = $top ?? 0;
        $bottom = $bottom ?? 0;
        $left = $left ?? 0;
        $right = $right ?? 0;
        return imagecrop($image, ['x' => $left, 'y' => $top, 'width' => (imagesx($image) - $left - $right), 'height' => (imagesy($image) - $top - $bottom)]);
    }

    public static function saveImageAsWebp(GdImage $image, string $filename) {
        imagewebp($image, UPLOAD_DIR . "/" . $filename, 80);
    }
}
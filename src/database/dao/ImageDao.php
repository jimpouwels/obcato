<?php

namespace Obcato\Core\database\dao;

use Obcato\Core\modules\images\model\Image;

interface ImageDao {
    public function getImage(?int $imageId): ?Image;

    public function updateImage(Image $image): void;

    public function getAllImages(): array;

    public function searchImages(?string $keyword, ?string $filename, ?int $limit): array;

    public function createImage(): Image;

    public function deleteImage($image): void;
}
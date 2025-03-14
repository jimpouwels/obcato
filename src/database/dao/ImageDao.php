<?php

namespace Obcato\Core\database\dao;

use Obcato\Core\modules\images\model\Image;
use Obcato\Core\modules\images\model\ImageLabel;

interface ImageDao {
    public function getImage(?int $imageId): ?Image;

    public function updateImage(Image $image): void;

    public function getAllImages(): array;

    public function getAllImagesWithoutLabel(): array;

    public function searchImagesByLabels(array $labels): array;

    public function searchImages(?string $keyword, ?string $filename, ?int $labelId): array;

    public function createImage(): Image;

    public function deleteImage($image): void;

    public function createLabel(): ImageLabel;

    public function getAllLabels(): array;

    public function getLabel(int $id): ?ImageLabel;

    public function getLabelByName(string $name): ?ImageLabel;

    public function persistLabel(ImageLabel $label): string;

    public function updateLabel(ImageLabel $label): void;

    public function deleteLabel(ImageLabel $label): void;

    public function addLabelToImage(int $labelId, Image $image): void;

    public function deleteLabelForImage(int $labelId, Image $image): void;

    public function getLabelsForImage(int $imageId): array;
}
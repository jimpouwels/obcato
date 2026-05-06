<?php

namespace Pageflow\Core\database\dao;

use Pageflow\Core\modules\images\model\FunctionalImage;
use Pageflow\Core\modules\images\model\FunctionalImageFolder;

interface FunctionalImageDao {
    public function getFunctionalImage(?int $id): ?FunctionalImage;
    public function getFunctionalImageFolder(?int $id): ?FunctionalImageFolder;
    public function getFolderTree(): array;
    public function getAllFunctionalImages(): array;
    public function createFunctionalImage(FunctionalImage $image): void;
    public function updateFunctionalImage(FunctionalImage $image): void;
    public function deleteFunctionalImage(FunctionalImage $image): void;
    public function createFolder(FunctionalImageFolder $folder): void;
    public function updateFolder(FunctionalImageFolder $folder): void;
    public function deleteFolder(int $id): void;
    public function moveImageToFolder(FunctionalImage $image, ?int $folderId): void;
}

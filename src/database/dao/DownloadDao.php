<?php

namespace Obcato\Core\database\dao;

use Obcato\Core\modules\downloads\model\Download;

interface DownloadDao {
    public function getDownload(string $id): ?Download;

    public function persistDownload(Download $download): void;

    public function updateDownload(Download $download): void;

    public function getAllDownloads(): array;

    public function searchDownloads(string $searchQuery): array;

    public function deleteDownload(int $id): void;
}
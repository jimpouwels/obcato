<?php

interface DownloadDao {
    public function getDownload(string $id): ?Download;

    public function persistDownload(Download $download): void;

    public function updateDownload(Download $download): void;

    public function getAllDownloads(): array;

    public function searchDownloads(string $search_query): array;

    public function deleteDownload(int $id): void;
}
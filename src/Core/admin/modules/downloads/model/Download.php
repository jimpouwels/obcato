<?php

namespace Obcato\Core;

class Download extends Entity {

    private string $title;
    private string $filename;
    private bool $published;
    private string $createdAt;
    private int $createdById;

    public static function constructFromRecord(array $row): Download {
        $download = new Download();
        $download->initFromDb($row);
        return $download;
    }

    protected function initFromDb(array $row): void {
        $this->setTitle($row['title']);
        $this->setPublished($row['published']);
        $this->setCreatedAt($row['created_at']);
        $this->setCreatedById($row['created_by']);
        $this->setFilename($row['file_name']);
        parent::initFromDb($row);
    }

    public function setCreatedById(int $createdById): void {
        $this->createdById = $createdById;
    }

    public function getCreatedById(): int {
        return $this->createdById;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function setTitle(string $title): void {
        $this->title = $title;
    }

    public function isPublished(): bool {
        return $this->published;
    }

    public function setPublished(bool $published): void {
        $this->published = $published;
    }

    public function getCreatedAt(): string {
        return $this->createdAt;
    }

    public function setCreatedAt(string $created_at): void {
        $this->createdAt = $created_at;
    }

    public function getExtension(): string {
        $parts = explode(".", $this->getFilename());
        return $parts[count($parts) - 1];
    }

    public function getFilename(): string {
        return $this->filename;
    }

    public function setFilename(string $filename): void {
        $this->filename = $filename;
    }
}
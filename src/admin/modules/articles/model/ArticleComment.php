<?php

namespace Obcato\Core\admin\modules\articles\model;

use Obcato\Core\admin\core\model\Entity;

class ArticleComment extends Entity {

    private string $name;
    private string $emailAddress;
    private string $message;
    private ?int $parentId = null;
    private string $createdAt;

    public static function constructFromRecord(array $row): ArticleComment {
        $articleComment = new ArticleComment();
        $articleComment->initFromDb($row);
        return $articleComment;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function getEmailAddress(): string {
        return $this->emailAddress;
    }

    public function setEmailAddress(string $emailAddress): void {
        $this->emailAddress = $emailAddress;
    }

    public function getMessage(): string {
        return $this->message;
    }

    public function setMessage(string $message): void {
        $this->message = $message;
    }

    public function getParentId(): ?int {
        return $this->parentId;
    }

    public function setParentId(?int $parentId): void {
        $this->parentId = $parentId;
    }

    public function getCreatedAt(): string {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void {
        $this->createdAt = $createdAt;
    }

    protected function initFromDb(array $row): void {
        $this->setName($row['name']);
        $this->setEmailAddress($row['email_address']);
        $this->setMessage($row['message']);
        $this->setParentId($row['parent']);
        $this->setCreatedAt($row['created_at']);
        parent::initFromDb($row);
    }

}
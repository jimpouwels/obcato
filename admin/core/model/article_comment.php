<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "core/model/entity.php";

class ArticleComment extends Entity {

    private string $_name;
    private string $_email_address;
    private string $_message;
    private ?int $_parent_id = null;
    private string $_created_at;

    public static function constructFromRecord(array $row): ArticleComment {
        $article_comment = new ArticleComment();
        $article_comment->initFromDb($row);
        return $article_comment;
    }

    protected function initFromDb(array $row): void {
        $this->setName($row['name']);
        $this->setEmailAddress($row['email_address']);
        $this->setMessage($row['message']);
        $this->setParentId($row['parent']);
        $this->setCreatedAt($row['created_at']);
        parent::initFromDb($row);
    }

    public function getName(): string {
        return $this->_name;
    }

    public function setName(string $name): void {
        $this->_name = $name;
    }

    public function getEmailAddress(): string {
        return $this->_email_address;
    }

    public function setEmailAddress(string $email_address): void {
        $this->_email_address = $email_address;
    }

    public function getMessage(): string {
        return $this->_message;
    }

    public function setMessage(string $message): void {
        $this->_message = $message;
    }

    public function getParentId(): ?int {
        return $this->_parent_id;
    }

    public function setParentId(?int $parent_id): void {
        $this->_parent_id = $parent_id;
    }

    public function getCreatedAt(): string {
        return $this->_created_at;
    }

    public function setCreatedAt(string $created_at): void {
        $this->_created_at = $created_at;
    }

}
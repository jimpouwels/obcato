<?php
require_once CMS_ROOT . "/core/model/Entity.php";
require_once CMS_ROOT . "/database/dao/ElementHolderDaoMysql.php";

class Link extends Entity {

    const INTERNAL = "INTERNAL";
    const EXTERNAL = "EXTERNAL";
    private ?string $title = null;
    private ?string $url = null;
    private string $type;
    private ?string $code = null;
    private ?int $targetElementHolderId = null;
    private int $parentElementHolderId;
    private ?string $target = null;
    private ElementHolderDao $elementHolderDao;

    public function __construct() {
        $this->elementHolderDao = ElementHolderDaoMysql::getInstance();
    }

    public static function constructFromRecord(array $row): Link {
        $link = new Link();
        $link->initFromDb($row);
        return $link;
    }

    protected function initFromDb(array $row): void {
        $this->setTitle($row['title']);
        $this->setTargetAddress($row['target_address']);
        $this->setType($row['type']);
        $this->setCode($row['code']);
        $this->setTarget($row['target']);
        $this->setParentElementHolderId($row['parent_element_holder']);
        $this->setTargetElementHolderId($row['target_element_holder']);
        parent::initFromDb($row);
    }

    public function setTargetAddress(?string $url): void {
        $this->url = $url;
    }

    public function getTitle(): ?string {
        return $this->title;
    }

    public function setTitle(?string $title): void {
        $this->title = $title;
    }

    public function getTargetAddress(): ?string {
        return $this->url;
    }

    public function getType(): string {
        return $this->type;
    }

    public function setType(string $type): void {
        $this->type = $type;
    }

    public function getTargetElementHolder(): ?ElementHolder {
        $elementHolder = null;
        if ($this->targetElementHolderId) {
            $elementHolder = $this->getElementHolder($this->targetElementHolderId);
        }
        return $elementHolder;
    }

    private function getElementHolder(int $elementHolderId): ElementHolder {
        return $this->elementHolderDao->getElementHolder($elementHolderId);
    }

    public function getParentElementHolder(): ?ElementHolder {
        if ($this->parentElementHolderId) {
            return $this->getElementHolder($this->parentElementHolderId);
        }
        return null;
    }

    public function getTargetElementHolderId(): ?int {
        return $this->targetElementHolderId;
    }

    public function setTargetElementHolderId(?int $targetElementHolderId): void {
        $this->targetElementHolderId = $targetElementHolderId;
    }

    public function getParentElementHolderId(): int {
        return $this->parentElementHolderId;
    }

    public function setParentElementHolderId(int $parentElementHolderId): void {
        $this->parentElementHolderId = $parentElementHolderId;
    }

    public function getCode(): ?string {
        return $this->code;
    }

    public function setCode(?string $code): void {
        $this->code = $code;
    }

    public function getTarget(): ?string {
        return $this->target;
    }

    public function setTarget(?string $target): void {
        $this->target = $target;
    }
}
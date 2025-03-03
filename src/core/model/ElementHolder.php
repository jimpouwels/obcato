<?php

namespace Obcato\Core\core\model;

use DateTime;
use Obcato\Core\database\dao\ElementDaoMysql;
use Obcato\Core\database\dao\LinkDaoMysql;
use Obcato\Core\modules\templates\model\Presentable;

class ElementHolder extends Presentable {

    private string $name;
    private string $title;
    private bool $published;
    private string $createdAt;
    private int $createdById;
    private DateTime $lastModified;
    private array $elements = array();
    private array $links = array();
    private string $type;

    public function __construct(int $scopeId) {
        parent::__construct($scopeId);
    }

    public static function constructFromRecord(array $row): ElementHolder {
        $element_holder = new ElementHolder($row["scope_id"]);
        $element_holder->initFromDb($row);

        return $element_holder;
    }

    public function setCreatedById(int $createdById): void {
        $this->createdById = $createdById;
    }

    public function isPublished(): bool {
        return $this->published;
    }

    public function setPublished(bool $published): void {
        $this->published = $published;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function setTitle(string $title): void {
        $this->title = $title;
    }

    public function addElement(Element $element): void {
        $this->elements[] = $element;
    }

    public function deleteElement(Element $element_to_delete): void {
        $this->elements = array_filter($this->elements, fn($element) => $element->getId() != $element_to_delete->getId());
    }

    public function getCreatedAt(): string {
        return $this->createdAt;
    }

    public function setCreatedAt(string $created_at): void {
        $this->createdAt = $created_at;
    }

    public function getCreatedById(): int {
        return $this->createdById;
    }

    public function getLastModified(): DateTime {
        return $this->lastModified;
    }

    public function setLastModified(DateTime $last_modified): void {
        $this->lastModified = $last_modified;
    }

    public function getLinks(): array {
        return $this->links;
    }

    public function setLinks(array $links): void {
        $this->links = $links;
    }

    public function addLink(Link $link): void {
        $this->links[] = $link;
    }

    public function deleteLink(Link $linkToDelete): void {
        $this->links = array_filter($this->links, fn($link) => $linkToDelete->getId() != $link->getId());
    }

    public function getElementStatics(): array {
        $elementDao = ElementDaoMysql::getInstance();
        $element_statics = array();
        foreach ($this->getElements() as $element) {
            $key = $elementDao->getElementTypeForElement($element->getId())->getIdentifier();
            if (!array_key_exists($key, $element_statics)) {
                $statics = $element->getStatics();
                if (!is_null($statics)) {
                    $element_statics[$key] = $element->getStatics();
                }
            }
        }
        return $element_statics;
    }

    public function getElements(): array {
        usort($this->elements, function (Element $e1, Element $e2) {
            return $e1->getOrderNr() - $e2->getOrderNr();
        });
        return $this->elements;
    }

    public function setElements(array $elements): void {
        $this->elements = $elements;
    }

    public function getType(): string {
        return $this->type;
    }

    public function setType(string $type): void {
        $this->type = $type;
    }

    protected function initFromDb(array $row): void {
        $this->setName($row['name']);
        $this->setTitle($row['title']);
        $this->setPublished($row['published'] == 1);
        $this->setCreatedAt($row['created_at']);
        $this->setCreatedById($row['created_by']);
        $this->setLastModified(new DateTime($row['last_modified']));
        $this->setType($row['type']);
        parent::initFromDb($row);
        $this->setElements(ElementDaoMysql::getInstance()->getElements($this));
        $this->setLinks(LinkDaoMysql::getInstance()->getLinksForElementHolder($this->getId()));
    }

}
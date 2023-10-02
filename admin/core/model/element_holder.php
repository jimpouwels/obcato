<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/core/model/presentable.php";
require_once CMS_ROOT . "/database/dao/link_dao.php";
require_once CMS_ROOT . "/database/dao/authorization_dao.php";

class ElementHolder extends Presentable {

    private string $_title;
    private bool $_published;
    private string $_created_at;
    private int $_created_by_id;
    private DateTime $_last_modified;
    private array $_elements = array();
    private array $_links = array();
    private string $_type;

    public function __construct(int $scope_id) {
        parent::__construct($scope_id);
    }

    public static function constructFromRecord(array $row): ElementHolder {
        $element_holder = new ElementHolder($row["scope_id"]);
        $element_holder->initFromDb($row);

        return $element_holder;
    }

    protected function initFromDb(array $row): void {
        require_once CMS_ROOT . '/database/dao/element_dao.php';
        $this->setTitle($row['title']);
        $this->setPublished($row['published'] == 1);
        $this->setCreatedAt($row['created_at']);
        $this->setCreatedById($row['created_by']);
        $this->setLastModified(new DateTime($row['last_modified']));
        $this->setType($row['type']);
        parent::initFromDb($row);
        $this->setElements(ElementDao::getInstance()->getElements($this));
        $this->setLinks(LinkDao::getInstance()->getLinksForElementHolder($this->getId()));
    }

    public function setCreatedById(int $created_by_id): void {
        $this->_created_by_id = $created_by_id;
    }

    public function isPublished(): bool {
        return $this->_published;
    }

    public function setPublished(bool $published): void {
        $this->_published = $published;
    }

    public function getTitle(): string {
        return $this->_title;
    }

    public function setTitle(string $title): void {
        $this->_title = $title;
    }

    public function addElement(Element $element): void {
        $this->_elements[] = $element;
    }

    public function deleteElement(Element $element_to_delete): void {
        $this->_elements = array_filter($this->_elements, function ($element) use ($element_to_delete) {
            return $element->getId() !== $element_to_delete->getId();
        });
    }

    public function getCreatedAt(): string {
        return $this->_created_at;
    }

    public function setCreatedAt(string $created_at): void {
        $this->_created_at = $created_at;
    }

    public function getCreatedBy(): User {
        $authorization_dao = AuthorizationDao::getInstance();
        return $authorization_dao->getUserById($this->_created_by_id);
    }

    public function getLastModified(): DateTime {
        return $this->_last_modified;
    }

    public function setLastModified(DateTime $last_modified): void {
        $this->_last_modified = $last_modified;
    }

    public function getLinks(): array {
        return $this->_links;
    }

    public function setLinks(array $links): void {
        $this->_links = $links;
    }

    public function getElementStatics(): array {
        $element_statics = array();
        foreach ($this->getElements() as $element) {
            $key = $element->getType()->getIdentifier();
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
        usort($this->_elements, function (Element $e1, Element $e2) {
            return $e1->getOrderNr() - $e2->getOrderNr();
        });
        return $this->_elements;
    }

    public function setElements(array $elements): void {
        $this->_elements = $elements;
    }

    public function getType(): string {
        return $this->_type;
    }

    public function setType(string $type): void {
        $this->_type = $type;
    }

}
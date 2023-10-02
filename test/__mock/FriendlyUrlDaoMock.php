<?php

require_once(CMS_ROOT . '/database/dao/FriendlyUrlDao.php');

class FriendlyUrlDaoMock implements FriendlyUrlDao {

    private array $elementHolders = array();

    public function insertFriendlyUrl(string $url, ElementHolder $element_holder): void {
        // TODO: Implement insertFriendlyUrl() method.
    }

    public function updateFriendlyUrl(string $url, ElementHolder $element_holder): void {
        // TODO: Implement updateFriendlyUrl() method.
    }

    public function getUrlFromElementHolder(ElementHolder $element_holder): ?string {
        // TODO: Implement getUrlFromElementHolder() method.
    }

    public function getElementHolderIdFromUrl(string $url): ?int {
        foreach ($this->elementHolders as $data) {
            if ($data["url"] == $url) {
                return $data["element_holder"]->getId();
            }
        }
        return null;
    }

    public function addElementHolder(ElementHolder $elementHolder, string $url): void {
        $this->elementHolders[] = array("element_holder" => $elementHolder, "url" => $url);
    }

}
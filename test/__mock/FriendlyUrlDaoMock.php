<?php

require_once(CMS_ROOT . '/database/dao/FriendlyUrlDao.php');

class FriendlyUrlDaoMock implements FriendlyUrlDao {

    private array $_data = array();

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
        foreach ($this->_data as $data) {
            if ($data["url"] == $url) {
                return $data["page"]->getId();
            }
        }
        return null;
    }

    public function addPage(Page $page, string $url): void {
        $this->_data[] = array("page" => $page,
            "url" => $url);
    }
}
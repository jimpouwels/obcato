<?php

require_once(CMS_ROOT . '/database/dao/FriendlyUrlDao.php');

class FriendlyUrlDaoMock implements FriendlyUrlDao {

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
        return 0;
    }
}
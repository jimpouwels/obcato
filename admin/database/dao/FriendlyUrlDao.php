<?php
defined('_ACCESS') or die;

interface FriendlyUrlDao {
    public function insertFriendlyUrl(string $url, ElementHolder $element_holder): void;

    public function updateFriendlyUrl(string $url, ElementHolder $element_holder): void;

    public function getUrlFromElementHolder(ElementHolder $element_holder): ?string;

    public function getElementHolderIdFromUrl(string $url): ?int;
}
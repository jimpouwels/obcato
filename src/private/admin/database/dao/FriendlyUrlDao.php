<?php

namespace Obcato\Core;

interface FriendlyUrlDao {
    public function insertFriendlyUrl(string $url, ElementHolder $elementHolder): void;

    public function updateFriendlyUrl(string $url, ElementHolder $elementHolder): void;

    public function getUrlFromElementHolder(ElementHolder $elementHolder): ?string;

    public function getElementHolderIdFromUrl(string $url): ?int;
}
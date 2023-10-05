<?php

interface LinkDao {
    public function createLink(int $element_holder_id, $title): Link;

    public function persistLink(Link $new_link): void;

    public function getLinksForElementHolder(int $element_holder_id): array;

    public function deleteLink(Link $link);

    public function updateLink(Link $link);

    public function getBrokenLinks(): array;
}
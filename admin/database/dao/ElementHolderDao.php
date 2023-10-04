<?php
defined('_ACCESS') or die;

interface ElementHolderDao {
    public function getElementHolder(int $id): ?ElementHolder;

    public function persist(ElementHolder $elementHolder): void;

    public function update(ElementHolder $element_holder): void;

    public function delete(ElementHolder $element_holder): void;
}
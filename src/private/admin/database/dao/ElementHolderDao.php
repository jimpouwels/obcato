<?php

interface ElementHolderDao {
    public function getElementHolder(int $id): ?ElementHolder;

    public function persist(ElementHolder $elementHolder): void;

    public function update(ElementHolder $elementHolder): void;

    public function delete(ElementHolder $elementHolder): void;
}
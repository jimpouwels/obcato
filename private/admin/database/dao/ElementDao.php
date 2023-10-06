<?php

interface ElementDao {
    public function getElements(ElementHolder $elementHolder): array;

    public function getElement(int $id): ?Element;

    public function updateElement(Element $element): void;

    public function deleteElement(Element $element): void;

    public function getElementTypes(): array;

    public function updateElementType(ElementType $elementType): void;

    public function persistElementType(ElementType $elementType): void;

    public function deleteElementType(ElementType $elementType): void;

    public function getElementType(int $elementTypeId): ?ElementType;

    public function getElementTypeByIdentifier(string $identifier): ?ElementType;

    public function getElementTypeForElement(int $elementId): ?ElementType;

    public function createElement(ElementType $elementType, int $elementHolderId): Element;
}
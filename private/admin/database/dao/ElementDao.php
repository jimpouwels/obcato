<?php

interface ElementDao {
    public function getElements(ElementHolder $element_holder): array;

    public function getElement(int $id): ?Element;

    public function updateElement(Element $element): void;

    public function deleteElement(Element $element): void;

    public function getElementTypes(): array;

    public function updateElementType(ElementType $element_type): void;

    public function persistElementType(ElementType $element_type): void;

    public function deleteElementType(ElementType $element_type): void;

    public function getDefaultElementTypes(): array;

    public function getCustomElementTypes(): array;

    public function getElementType(int $element_type_id): ?ElementType;

    public function getElementTypeByIdentifier(string $identifier): ?ElementType;

    public function getElementTypeForElement(int $element_id): ?ElementType;

    public function createElement(ElementType $element_type, int $element_holder_id): Element;
}
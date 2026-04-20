<?php

namespace Obcato\Core\service;

use Obcato\Core\core\model\ElementHolder;
use Obcato\Core\core\model\ElementType;
use Obcato\Core\core\model\Element;

interface ElementHolderService {

    public function updateElementHolder(ElementHolder $elementHolder): void;

    public function addElementToElementHolder(ElementType $elementType, ElementHolder $elementHolder): Element;

    public function getElementHolder(int $id): ?ElementHolder;

}
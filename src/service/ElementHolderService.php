<?php

namespace Pageflow\Core\service;

use Pageflow\Core\core\model\ElementHolder;
use Pageflow\Core\core\model\ElementType;
use Pageflow\Core\core\model\Element;

interface ElementHolderService {

    public function updateElementHolder(ElementHolder $elementHolder): void;

    public function addElementToElementHolder(ElementType $elementType, ElementHolder $elementHolder): Element;

    public function getElementHolder(int $id): ?ElementHolder;

}
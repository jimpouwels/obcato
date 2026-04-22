<?php

namespace Pageflow\Core\service;

use Pageflow\Core\database\dao\ElementHolderDao;
use Pageflow\Core\database\dao\ElementHolderDaoMysql;
use Pageflow\Core\core\model\ElementHolder;
use Pageflow\Core\database\dao\ElementDao;
use Pageflow\Core\database\dao\ElementDaoMysql;
use Pageflow\Core\core\model\ElementType;
use Pageflow\Core\core\model\Element;
use Pageflow\Core\service\ElementHolderService;
use const Pageflow\CMS_ROOT;

class ElementHolderInteractor implements ElementHolderService {

    private ElementHolderDao $elementHolderDao;
    private ElementDao $elementDao;

    public function __construct() {
        $this->elementHolderDao = ElementHolderDaoMysql::getInstance();
        $this->elementDao = ElementDaoMysql::getInstance();
    }

    public function updateElementHolder(ElementHolder $elementHolder): void {
        $elementHolder->setVersion($elementHolder->getVersion() + 1);
        $this->elementHolderDao->update($elementHolder);
    }

    public function addElementToElementHolder(ElementType $elementType, ElementHolder $elementHolder): Element {
        require_once CMS_ROOT . "/elements/" . $elementType->getIdentifier() . "/" . $elementType->getDomainObject();
        $elementClassName = "Pageflow\\Core\\elements\\" . $elementType->getIdentifier() . "\\" . $elementType->getClassName();
        $newElement = new $elementClassName($elementType->getScopeId());
        $newElement->setElementHolderId($elementHolder->getId());
        $newElement->setOrderNr(999);

        $element = $this->elementDao->insertElement($elementType, $newElement);
        $elementHolder->addElement($element);
        $element->setElementHolderId($elementHolder->getId());
        $this->bumpElementHolderVersion($elementHolder);
        return $element;
    }

    public function getElementHolder(int $id): ?ElementHolder {
        return $this->elementHolderDao->getElementHolder($id);
    }

    private function bumpElementHolderVersion(ElementHolder $elementHolder): void {
        $elementHolder->setVersion($elementHolder->getVersion() + 1);
        $this->elementHolderDao->updateVersion($elementHolder);
    }

}
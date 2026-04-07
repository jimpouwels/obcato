<?php

namespace Obcato\Core\view\views;

use Obcato\Core\core\BlackBoard;
use Obcato\Core\core\model\ModuleGroup;
use Obcato\Core\database\dao\ElementDao;
use Obcato\Core\database\dao\ElementDaoMysql;

class NavigationMenu extends Visual {

    private array $moduleGroups;
    private ElementDao $elementDao;
    private ?int $activeModuleId;
    private bool $isElementHolder;

    public function __construct(array $moduleGroups, ?int $activeModuleId = null, bool $isElementHolder = false) {
        parent::__construct();
        $this->moduleGroups = $moduleGroups;
        $this->elementDao = ElementDaoMysql::getInstance();
        $this->activeModuleId = $activeModuleId;
        $this->isElementHolder = $isElementHolder;
    }

    public function getTemplateFilename(): string {
        return "navigation_menu.tpl";
    }

    public function load(): void {
        $groups = array();
        foreach ($this->moduleGroups as $moduleGroup) {
            if ($moduleGroup->isElementGroup() && !$this->isElementHolder) {
                continue;
            }
            
            $group = array();
            $group['title'] = $this->getTextResource('menu_item_' . $moduleGroup->getIdentifier());
            if ($moduleGroup->isElementGroup()) {
                $group['elements'] = $this->renderElementsMenuItem();
            } else {
                $group['modules'] = $this->renderMenuItem($moduleGroup);
            }
            $groups[] = $group;
        }
        $this->assign('groups', $groups);
    }

    private function renderMenuItem(ModuleGroup $moduleGroup): array {
        $subItems = array();
        $modules = $moduleGroup->getModules();
        $count = 1;
        foreach ($modules as $module) {
            $subItem = array();
            $subItem["title"] = $this->getTextResource($module->getIdentifier() . '_module_title');
            $subItem["id"] = $module->getId();
            $subItem["popup"] = $module->isPopUp();
            $subItem["icon_url"] = '/admin?file=/modules/' . $module->getIdentifier() . '/img/' . $module->getIdentifier() . '.png';
            $subItem["last"] = ($count == count($modules));
            $subItem["active"] = ($module->getId() == $this->activeModuleId);
            $count++;
            $subItems[] = $subItem;
        }
        return $subItems;
    }

    private function renderElementsMenuItem(): array {
        $sub_items = array();
        foreach ($this->elementDao->getElementTypes() as $elementType) {
            $sub_item = array();
            $sub_item["id"] = $elementType->getId();
            $sub_item["name"] = $this->getTextResource($elementType->getIdentifier() . '_label');
            $sub_item["icon_url"] = BlackBoard::getElementFileUrl($elementType->getIdentifier(), 'img/' . $elementType->getIdentifier() . '.png');
            $sub_items[] = $sub_item;
        }
        return $sub_items;
    }

}
<?php

namespace Obcato\Core;

use Obcato\ComponentApi\TemplateEngine;
use Obcato\ComponentApi\Visual;

class NavigationMenu extends Visual {

    private array $moduleGroups;
    private ElementDao $elementDao;

    public function __construct(TemplateEngine $templateEngine, array $module_groups) {
        parent::__construct($templateEngine);
        $this->moduleGroups = $module_groups;
        $this->elementDao = ElementDaoMysql::getInstance();
    }

    public function getTemplateFilename(): string {
        return "system/navigation_menu.tpl";
    }

    public function load(): void {
        $groups = array();
        foreach ($this->moduleGroups as $moduleGroup) {
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
            $subItem["icon_url"] = '/admin/static.php?file=/modules/' . $module->getIdentifier() . '/img/' . $module->getIdentifier() . '.png';
            $subItem["last"] = ($count == count($modules));
            $count++;
            $subItems[] = $subItem;
        }
        return $subItems;
    }

    private function renderElementsMenuItem(): array {
        $subItems = array();
        foreach ($this->elementDao->getElementTypes() as $elementType) {
            $sub_item = array();
            $sub_item["id"] = $elementType->getId();
            $sub_item["name"] = $this->getTextResource($elementType->getIdentifier() . '_label');
            $sub_item["icon_url"] = '/admin/static.php?file=/elements/' . $elementType->getIdentifier() . "/img/" . $elementType->getIdentifier() . ".png";
            $subItems[] = $sub_item;
        }
        return $subItems;
    }

}
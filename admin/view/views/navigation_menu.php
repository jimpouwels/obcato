<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/visual.php";
    require_once CMS_ROOT . "database/dao/module_dao.php";
    require_once CMS_ROOT . "database/dao/element_dao.php";
    
    class NavigationMenu extends Visual {
    
        private static string $TEMPLATE = "system/navigation_menu.tpl";
        private array $_module_groups;
        private ElementDao $_element_dao;
    
        public function __construct(array $module_groups) {
            parent::__construct();
            $this->_module_groups = $module_groups;
            $this->_element_dao = ElementDao::getInstance();
        }
    
        public function render(): string {
            $groups = array();
            foreach ($this->_module_groups as $module_group) {
                $group = array();
                $group['title'] = $this->getTextResource('menu_item_' . $module_group->getIdentifier());
                if ($module_group->isElementGroup()) {
                    $group['elements'] = $this->renderElementsMenuItem();
                } else {
                    $group['modules'] = $this->renderMenuItem($module_group);
                }
                $groups[] = $group;
            }
            $this->getTemplateEngine()->assign('groups', $groups);
            return $this->getTemplateEngine()->fetch(self::$TEMPLATE);
        }
        
        private function renderMenuItem(ModuleGroup $module_group): array {
            $sub_items = array();
            $modules = $module_group->getModules();
            $count = 1;
            foreach ($modules as $module) {
                $sub_item = array();
                $sub_item["title"] = $this->getTextResource($module->getTitleTextResourceIdentifier());
                $sub_item["id"] = $module->getId();
                $sub_item["popup"] = $module->isPopUp();
                $sub_item["icon_url"] = '/admin/static.php?file=/modules/' . $module->getIdentifier() . $module->getIconUrl();
                $sub_item["last"] = ($count == count($modules));
                $count++;
                $sub_items[] = $sub_item;
            }
            return $sub_items;
        }
        
        private function renderElementsMenuItem(): array {
            $sub_items = array();
            foreach ($this->_element_dao->getElementTypes() as $element_type) {
                $sub_item = array();
                $sub_item["id"] = $element_type->getId();
                $sub_item["name"] = $element_type->getName();
                $sub_item["icon_url"] = '/admin/static.php?file=/elements/' . $element_type->getIdentifier() . $element_type->getIconUrl();
                $sub_items[] = $sub_item;
            }
            return $sub_items;
        }
    
    }
<?php

	// No direct access
	defined('_ACCESS') or die;
	
	require_once FRONTEND_REQUEST . "view/views/visual.php";
	require_once FRONTEND_REQUEST . "database/dao/module_dao.php";
	require_once FRONTEND_REQUEST . "database/dao/element_dao.php";
	
	class NavigationMenu extends Visual {
	
		private static $TEMPLATE = "system/navigation_menu.tpl";
		private $myModuleGroups;
	
		public function __construct($module_groups) {
			$this->myModuleGroups = $module_groups;
		}
	
		public function render() {
			$menu_items = array();			
			foreach ($this->myModuleGroups as $module_group) {
				$title = $module_group->getTitle();
				if ($module_group->isElementGroup()) {
					$menu_items[$title] = $this->renderElementsMenuItem($module_group);
				} else {
					$menu_items[$title] = $this->renderMenuItem($module_group);
				}
			}
			
			$template_engine = TemplateEngine::getInstance();
			$template_engine->assign("menu_items", $menu_items);
			return $template_engine->fetch(self::$TEMPLATE);
		}
		
		private function renderMenuItem($module_group) {
			$sub_items = array();
			$modules = $module_group->getModules();
			$count = 1;
			foreach ($modules as $module) {
				$sub_items[] = $this->renderSubItem($module, $count == count($modules) ? true : false);
				$count++;
			}
			
			return $sub_items;
		}
		
		private function renderElementsMenuItem($module_group) {
			$element_dao = ElementDao::getInstance();
			$element_types = $element_dao->getElementTypes();
			
			$sub_items = array();
			foreach ($element_types as $element_type) {
				$sub_items[] = $this->renderElementSubItem($element_type);
			}
			// FIXME: Hardcoded link element - special element type
			$template_engine = TemplateEngine::getInstance();
			$sub_items[] = $template_engine->fetch("system/navigation_menu_link_element_item.tpl");
					
			return $sub_items;
		}
		
		private function renderSubItem($module, $last_item) {
			$template_engine = TemplateEngine::getInstance();
			$template_engine->assign("title", $module->getTitle());
			$template_engine->assign("id", $module->getId());
			$template_engine->assign("popup", $module->isPopUp());
			$template_engine->assign("icon_url", $module->getIconUrl());
			$template_engine->assign("last", $last_item);
			
			return $template_engine->fetch("system/navigation_menu_sub_item.tpl");
		}
		
		private function renderElementSubItem($element_type) {
			$template_engine = TemplateEngine::getInstance();
			$template_engine->assign("id", $element_type->getId());
			$template_engine->assign("name", $element_type->getName());
			$template_engine->assign("icon_url", $element_type->getIconUrlAbsolute());
			
			return $template_engine->fetch("system/navigation_menu_element_sub_item.tpl");
		}
	
	}

?>
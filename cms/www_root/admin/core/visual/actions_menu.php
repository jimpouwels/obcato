<?php

	// No direct access
	defined('_ACCESS') or die;
	
	require_once "core/visual/visual.php";
	
	class ActionsMenu extends Visual {
	
		private static $TEMPLATE = "system/actions_menu.tpl";
		private $myActionButtons;
	
		public function __construct($action_buttons) {
			$this->myActionButtons = $action_buttons;
		}
	
		public function render() {
			$buttons_html = $this->getActionButtonsHtml();
			
			$template_engine = TemplateEngine::getInstance();
			$template_engine->assign("buttons", $buttons_html);
			return $template_engine->fetch(self::$TEMPLATE);
		}
		
		private function getActionButtonsHtml() {
			$buttons_html = "";
			foreach ($this->myActionButtons as $action_button) {
				if (!is_null($action_button)) {
					$buttons_html .= $action_button->render();
				}
			}
			return $buttons_html;
		}
		
	}

?>
<?php

	// No direct access
	defined('_ACCESS') or die;
	
	require_once "libraries/system/template_engine.php";
	require_once "core/visual/information_message.php";
	
	class ElementContainer extends Visual {
	
		private static $TEMPLATE = "system/element_container.tpl";
	
		private $_template_engine;
		private $_elements;
	
		public function __construct($elements) {
			$this->_template_engine = TemplateEngine::getInstance();
			$this->_elements = $elements;
		}
	
		public function render() {
			if (count($this->_elements) > 0) {
				$this->_template_engine->assign("elements", $this->renderElements());
			} else {
				$this->_template_engine->assign("message", $this->renderInformationMessage());
			}
			
			return $this->_template_engine->fetch(self::$TEMPLATE);
		}
		
		private function renderInformationMessage() {
			$information_message = new InformationMessage("Geen elementen gevonden. Ga naar \"Invoegen\" om een nieuw element toe te voegen.");
			return $information_message->render();
		}
		
		private function renderElements() {
			$elements = array();
			foreach ($this->_elements as $element) {
				$elements[] = $element->getEditForm()->render();
			}
			
			return $elements;
		}
	}

?>
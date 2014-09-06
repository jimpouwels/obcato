<?php

	// No direct access
	defined('_ACCESS') or die;

	require_once FRONTEND_REQUEST . "view/views/information_message.php";
	require_once FRONTEND_REQUEST . "view/views/object_picker.php";
	
	class LinkEditor extends Visual {
	
		private static $TEMPLATE = "system/link_editor.tpl";
		private $_links;
		private $_template_engine;
	
		public function __construct($links) {
			$this->_template_engine = TemplateEngine::getInstance();
			$this->_links = $links;
		}
	
		public function render() {
			$links_html = array();
			
			if (count($this->_links) > 0) {
				foreach ($this->_links as $link) {
					$link_html = array();
					$link_html['id'] = $link->getId();
					$link_html['code'] = $link->getCode();
					$title_field = new TextField("link_" . $link->getId() . "_title", "Titel", $link->getTitle(), false, false, null);
					$link_html['title_field'] = $title_field->render();
					$link_html['target_field'] = $this->getLinkTargetField($link);
					
					$link_target = $link->getTargetElementHolder();
					$target_title = "";
					if (!is_null($link_target)) {
						$target_title = $link_target->getTitle();
					}
					$link_html['target_title'] = $target_title;
					$code_field = new TextField("link_" . $link->getId() . "_code", "Code", $link->getCode(), false, false, null);
					$link_html['code_field'] = $code_field->render();
					$delete_field = new SingleCheckbox("link_" . $link->getId() . "_delete", "", false, false, "");
					$link_html['delete_field'] = $delete_field->render();
					$element_holder_picker = new ObjectPicker("", $link->getTargetElementHolderId(), "link_element_holder_ref_" . $link->getId(), "Selecteer linkdoel", "update_element_holder");
					$link_html['element_holder_picker'] = $element_holder_picker->render();
					
					$links_html[] = $link_html;
				}
				$this->_template_engine->assign("links", $links_html);
			} else {
				$message = new InformationMessage("Geen links gevonden. Klik op &quot;Invoegen&quot; &gt; &quot;Link&quot; om een nieuwe link toe te voegen.");
				$this->_template_engine->assign("message", $message->render());
			}
			
			return $this->_template_engine->fetch(self::$TEMPLATE);
		}
		
		private function getLinkTargetField($link) {
			$link_target_field = null;
			$link_target = $link->getTargetElementHolder();
			if (is_null($link_target)) {
				$target_text_field = new TextField("link_" . $link->getId() . "_url", "Titel", $link->getTargetAddress(), false, false, null);
				$link_target_field = $target_text_field->render();
			}
			return $link_target_field;
		}
	}

?>
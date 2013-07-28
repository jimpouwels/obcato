<?php	// No direct access	defined('_ACCESS') or die;		require_once "libraries/system/template_engine.php";	require_once "visual/form_textfield.php";	require_once "visual/form_checkbox_single.php";	require_once "visual/element_container.php";	require_once "visual/form_template_picker.php";	require_once "visual/link_editor.php";	require_once "dao/block_dao.php";		class BlockEditor extends Visual {			private static $BLOCK_EDITOR_TEMPLATE = "modules/blocks/blocks/editor.tpl";		private static $BLOCK_METADATA_TEMPLATE = "modules/blocks/blocks/metadata.tpl";			private $_template_engine;		private $_current_block;		private $_block_dao;			public function __construct($current_block) {			$this->_current_block = $current_block;			$this->_block_dao = BlockDao::getInstance();			$this->_template_engine = TemplateEngine::getInstance();		}			public function render() {			$this->assignElementHolderFormIds();			$this->_template_engine->assign("block_metadata", $this->renderBlockMetaData());			$this->_template_engine->assign("element_container", $this->renderElementContainer());			$this->_template_engine->assign("link_editor", $this->renderLinkEditor());			$this->_template_engine->assign("id", $this->_current_block->getId());						return $this->_template_engine->fetch(self::$BLOCK_EDITOR_TEMPLATE);		}				private function renderBlockMetaData() {			$title_field = new TextField("title", "Titel", $this->_current_block->getTitle(), true, false, null);			$published_field = new SingleCheckbox("published", "Gepubliceerd", $this->_current_block->isPublished(), false, null);			$template_picker_field = new TemplatePicker("block_template", "Presentatie", false, "", $this->_current_block->getTemplate(), $this->_current_block->getScope());									$this->_template_engine->assign("title_field", $title_field->render());			$this->_template_engine->assign("published_field", $published_field->render());			$this->_template_engine->assign("template_picker_field", $template_picker_field->render());			$this->_template_engine->assign("positions_field", $this->renderPositionsField());			return $this->_template_engine->fetch(self::$BLOCK_METADATA_TEMPLATE);		}				private function renderPositionsField() {			$positions_options = array();			array_push($positions_options, array("name" => "&gt; Selecteer", "value" => null));			foreach ($this->_block_dao->getBlockPositions() as $position) {				array_push($positions_options, array("name" => $position->getName(), "value" => $position->getId()));			}						$current_position = null;			if (!is_null($this->_current_block->getPosition())) {				$current_position = $this->_current_block->getPosition()->getId();			}			$positions_field = new Pulldown("block_position", "Positie", $current_position, $positions_options, false, null);			return $positions_field->render();		}				private function getPublicationDateValue($publication_date) {			return DateUtility::mysqlDateToString($this->_current_block->getPublicationDate(), '-');		}				private function renderElementContainer() {			$element_container = new ElementContainer($this->_current_block->getElements());			return $element_container->render();		}				private function renderLinkEditor() {			$link_editor = new LinkEditor($this->_current_block->getLinks());			return $link_editor->render();		}				private function assignElementHolderFormIds() {			$this->_template_engine->assign("add_element_form_id", ADD_ELEMENT_FORM_ID);			$this->_template_engine->assign("edit_element_holder_id", EDIT_ELEMENT_HOLDER_ID);			$this->_template_engine->assign("element_holder_form_id", ELEMENT_HOLDER_FORM_ID);			$this->_template_engine->assign("action_form_id", ACTION_FORM_ID);			$this->_template_engine->assign("delete_element_form_id", DELETE_ELEMENT_FORM_ID);			$this->_template_engine->assign("element_order_id", ELEMENT_ORDER_ID);		}		}	?>
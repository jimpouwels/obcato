<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/view/views/element_container.php";
require_once CMS_ROOT . "/view/views/form_template_picker.php";
require_once CMS_ROOT . "/view/views/link_editor.php";
require_once CMS_ROOT . "/database/dao/block_dao.php";

class BlockMetadataEditor extends Panel {

    private Block $_current_block;
    private BlockDao $_block_dao;

    public function __construct(Block $current_block) {
        parent::__construct('Algemeen', 'block_meta');
        $this->_current_block = $current_block;
        $this->_block_dao = BlockDao::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "modules/blocks/blocks/metadata.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $title_field = new TextField("title", $this->getTextResource("blocks_edit_metadata_title_field_label"), $this->_current_block->getTitle(), true, false, null);
        $published_field = new SingleCheckbox("published", $this->getTextResource("blocks_edit_metadata_ispublished_field_label"), $this->_current_block->isPublished(), false, null);
        $template_picker_field = new TemplatePicker("block_template", $this->getTextResource("blocks_edit_metadata_template_field_label"), false, "", $this->_current_block->getTemplate(), $this->_current_block->getScope());

        $this->assignElementHolderFormIds($data);
        $data->assign("current_block_id", $this->_current_block->getId());
        $data->assign("title_field", $title_field->render());
        $data->assign("published_field", $published_field->render());
        $data->assign("template_picker_field", $template_picker_field->render());
        $data->assign("positions_field", $this->renderPositionsField());
    }

    private function renderPositionsField(): string {
        $positions_options = array();
        foreach ($this->_block_dao->getBlockPositions() as $position) {
            $positions_options[] = array("name" => $position->getName(), "value" => $position->getId());
        }
        $current_position = null;
        if (!is_null($this->_current_block->getPosition())) {
            $current_position = $this->_current_block->getPosition()->getId();
        }
        $positions_field = new Pulldown("block_position", $this->getTextResource("blocks_edit_metadata_position_field_label"), $current_position, $positions_options, false, null, true);
        return $positions_field->render();
    }

    private function assignElementHolderFormIds($data): void {
        $data->assign("add_element_form_id", ADD_ELEMENT_FORM_ID);
        $data->assign("edit_element_holder_id", EDIT_ELEMENT_HOLDER_ID);
        $data->assign("element_holder_form_id", ELEMENT_HOLDER_FORM_ID);
        $data->assign("action_form_id", ACTION_FORM_ID);
        $data->assign("delete_element_form_id", DELETE_ELEMENT_FORM_ID);
    }

}

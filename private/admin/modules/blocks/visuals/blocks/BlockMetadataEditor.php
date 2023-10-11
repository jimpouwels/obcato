<?php
require_once CMS_ROOT . "/view/views/ElementContainer.php";
require_once CMS_ROOT . "/view/views/TemplatePicker.php";
require_once CMS_ROOT . "/view/views/LinkEditor.php";
require_once CMS_ROOT . "/database/dao/BlockDaoMysql.php";

class BlockMetadataEditor extends Panel {

    private Block $currentBlock;
    private BlockDao $blockDao;

    public function __construct(Block $currentBlock) {
        parent::__construct('Algemeen', 'block_meta');
        $this->currentBlock = $currentBlock;
        $this->blockDao = BlockDaoMysql::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "modules/blocks/blocks/metadata.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $titleField = new TextField("title", $this->getTextResource("blocks_edit_metadata_title_field_label"), $this->currentBlock->getTitle(), true, false, null);
        $publishedField = new SingleCheckbox("published", $this->getTextResource("blocks_edit_metadata_ispublished_field_label"), $this->currentBlock->isPublished(), false, null);
        $templatePickerField = new TemplatePicker("block_template", $this->getTextResource("blocks_edit_metadata_template_field_label"), false, "", $this->currentBlock->getTemplate(), $this->currentBlock->getScope());

        $this->assignElementHolderFormIds($data);
        $data->assign("current_block_id", $this->currentBlock->getId());
        $data->assign("title_field", $titleField->render());
        $data->assign("published_field", $publishedField->render());
        $data->assign("template_picker_field", $templatePickerField->render());
        $data->assign("positions_field", $this->renderPositionsField());
    }

    private function renderPositionsField(): string {
        $positionsOptions = array();
        foreach ($this->blockDao->getBlockPositions() as $position) {
            $positionsOptions[] = array("name" => $position->getName(), "value" => $position->getId());
        }
        $currentPosition = null;
        if (!is_null($this->currentBlock->getPosition())) {
            $currentPosition = $this->currentBlock->getPosition()->getId();
        }
        $positionsField = new Pulldown("block_position", $this->getTextResource("blocks_edit_metadata_position_field_label"), $currentPosition, $positionsOptions, false, null, true);
        return $positionsField->render();
    }

    private function assignElementHolderFormIds($data): void {
        $data->assign("add_element_form_id", ADD_ELEMENT_FORM_ID);
        $data->assign("edit_element_holder_id", EDIT_ELEMENT_HOLDER_ID);
        $data->assign("element_holder_form_id", ELEMENT_HOLDER_FORM_ID);
        $data->assign("action_form_id", ACTION_FORM_ID);
        $data->assign("delete_element_form_id", DELETE_ELEMENT_FORM_ID);
    }

}

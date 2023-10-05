<?php
require_once CMS_ROOT . "/modules/blocks/visuals/positions/PositionEditor.php";
require_once CMS_ROOT . "/modules/blocks/visuals/positions/PositionList.php";

class PositionTab extends Visual {

    private static string $POSITION_QUERYSTRING_KEY = "position";

    private ?BlockPosition $_current_position;

    public function __construct(?BlockPosition $current_position) {
        parent::__construct();
        $this->_current_position = $current_position;
    }

    public function getTemplateFilename(): string {
        return "modules/blocks/positions/root.tpl";
    }

    public function load(): void {
        if ($this->isEditPositionMode()) {
            $this->assign("position_editor", $this->renderPositionEditor());
        }
        $this->assign("position_list", $this->renderPositionList());
    }

    public static function isEditPositionMode(): bool {
        return (isset($_GET[self::$POSITION_QUERYSTRING_KEY]) && $_GET[self::$POSITION_QUERYSTRING_KEY] != '');
    }

    private function renderPositionEditor(): string {
        $position_editor = new PositionEditor($this->_current_position);
        return $position_editor->render();
    }

    private function renderPositionList(): string {
        $position_list = new PositionList();
        return $position_list->render();
    }

}

?>
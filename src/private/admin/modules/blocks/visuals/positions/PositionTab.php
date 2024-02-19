<?php
require_once CMS_ROOT . "/modules/blocks/visuals/positions/PositionEditor.php";
require_once CMS_ROOT . "/modules/blocks/visuals/positions/PositionList.php";

class PositionTab extends Obcato\ComponentApi\Visual {

    private static string $POSITION_QUERYSTRING_KEY = "position";

    private ?BlockPosition $currentPosition;

    public function __construct(TemplateEngine $templateEngine, ?BlockPosition $currentPosition) {
        parent::__construct($templateEngine);
        $this->currentPosition = $currentPosition;
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
        return (new PositionEditor($this->getTemplateEngine(), $this->currentPosition))->render();
    }

    private function renderPositionList(): string {
        return (new PositionList($this->getTemplateEngine()))->render();
    }

}
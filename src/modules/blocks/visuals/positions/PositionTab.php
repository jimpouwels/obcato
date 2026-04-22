<?php

namespace Pageflow\Core\modules\blocks\visuals\positions;

use Pageflow\Core\modules\blocks\model\BlockPosition;
use Pageflow\Core\view\views\Visual;

class PositionTab extends Visual {

    private static string $POSITION_QUERYSTRING_KEY = "position";

    private ?BlockPosition $currentPosition;

    public function __construct(?BlockPosition $currentPosition) {
        parent::__construct();
        $this->currentPosition = $currentPosition;
    }

    public function getTemplateFilename(): string {
        return "blocks/templates/positions/root.tpl";
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
        return (new PositionEditor($this->currentPosition))->render();
    }

    private function renderPositionList(): string {
        return (new PositionList())->render();
    }

}
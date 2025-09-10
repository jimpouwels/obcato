<?php

namespace Obcato\Core\modules\articles\visuals\terms;

use Obcato\Core\modules\articles\model\ArticleTerm;
use Obcato\Core\view\views\Visual;

class TermTab extends Visual {

    private static string $TERM_QUERYSTRING_KEY = "term";
    private static string $NEW_TERM_QUERYSTRING_KEY = "new_term";

    private ?ArticleTerm $currentTerm;

    public function __construct(?ArticleTerm $currentTerm) {
        parent::__construct();
        $this->currentTerm = $currentTerm;
    }

    public function getTemplateFilename(): string {
        return "articles/templates/terms/root.tpl";
    }

    public function load(): void {
        if ($this->isEditTermMode()) {
            $this->assign("term_editor", $this->renderTermEditor());
        }
        $this->assign("term_list", $this->renderTermsList());
    }

    public static function isEditTermMode(): bool {
        return (isset($_GET[self::$TERM_QUERYSTRING_KEY]) && $_GET[self::$TERM_QUERYSTRING_KEY] != '') ||
            (isset($_GET[self::$NEW_TERM_QUERYSTRING_KEY]) && $_GET[self::$NEW_TERM_QUERYSTRING_KEY] == 'true');
    }

    private function renderTermEditor(): string {
        return (new TermEditor($this->currentTerm))->render();
    }

    private function renderTermsList(): string {
        return (new TermsList())->render();
    }
}
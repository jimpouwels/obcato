<?php

namespace Pageflow\Core\modules\links\visuals;

use Pageflow\Core\modules\links\model\ReusableLink;
use Pageflow\Core\view\TemplateData;
use Pageflow\Core\view\views\Panel;
use Pageflow\Core\view\views\TextField;

class LinkEditor extends Panel {

    private ReusableLink $link;

    public function __construct(ReusableLink $link) {
        parent::__construct('links_editor_link_panel_title', 'link_editor_panel');
        $this->link = $link;
    }

    public function getPanelContentTemplate(): string {
        return "links/templates/link_editor.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $titleField = new TextField('title', 'links_editor_title_label', $this->link->getTitle(), true, false, null);
        $urlField   = new TextField('url', 'links_editor_url_label', $this->link->getUrl(), false, false, null);

        $data->assign('link_id',     $this->link->getId());
        $data->assign('title_field', $titleField->render());
        $data->assign('url_field',   $urlField->render());
    }
}

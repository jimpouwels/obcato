<?php

namespace Pageflow\Core\view\views;

use Pageflow\Core\modules\links\database\dao\ReusableLinkDaoMysql;
use Pageflow\Core\view\TemplateData;

class LinkLookup extends FormField {

    private array $allLinks;

    public function __construct(
        string $name,
        ?string $labelResourceIdentifier,
        ?int $selectedLinkId,
        ?string $className = null
    ) {
        parent::__construct($name, $selectedLinkId !== null ? (string)$selectedLinkId : '', $labelResourceIdentifier, false, false, $className);
        $dao = ReusableLinkDaoMysql::getInstance();
        $this->allLinks = array_map(fn($l) => [
            'id'    => $l->getId(),
            'title' => $l->getTitle(),
            'url'   => $l->getUrl(),
        ], $dao->getAllLinks());
    }

    public function getFormFieldTemplateFilename(): string {
        return "link_lookup.tpl";
    }

    public function getFieldType(): string {
        return 'link_lookup';
    }

    public function loadFormField(TemplateData $data): void {
        $selectedId    = $this->getValue();
        $selectedTitle = '';
        $selectedUrl   = '';
        if ($selectedId !== '') {
            foreach ($this->allLinks as $link) {
                if ((string)$link['id'] === $selectedId) {
                    $selectedTitle = $link['title'];
                    $selectedUrl   = $link['url'];
                    break;
                }
            }
        }
        $data->assign('selected_link_id',    $selectedId);
        $data->assign('selected_link_title', $selectedTitle);
        $data->assign('selected_link_url',   $selectedUrl);
    }
}

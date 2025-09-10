<?php

namespace Obcato\Core\modules\articles\visuals\metadata;

use Obcato\Core\modules\articles\model\ArticleMetadataField;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\Panel;
use Obcato\Core\view\views\TextField;

class MetadataFieldEditor extends Panel {

    private ArticleMetadataField $currentMetadataField;

    public function __construct(ArticleMetadataField $currentMetadataField) {
        parent::__construct($this->getTextResource("articles_metdata_field_editor_title"), 'metadata_field_editor_panel');
        $this->currentMetadataField = $currentMetadataField;
    }

    public function getPanelContentTemplate(): string {
        return "articles/templates/metadata/editor.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign("id", $this->currentMetadataField->getId());
        $nameField = new TextField("name", $this->getTextResource("article_metadata_field_editor_name_field"), $this->currentMetadataField->getName(), true, false, null);
        $data->assign("name_field", $nameField->render());
        $defaultValueField = new TextField("default_value", $this->getTextResource("article_metadata_field_editor_default_value_field"), $this->currentMetadataField->getDefaultValue(), false, false, null);
        $data->assign("default_value_field", $defaultValueField->render());
    }

}

<?php

namespace Pageflow\Core\modules\images\visuals\functional;

use Pageflow\Core\modules\images\model\FunctionalImage;
use Pageflow\Core\utilities\ImageUtility;
use Pageflow\Core\view\TemplateData;
use Pageflow\Core\view\views\Panel;
use Pageflow\Core\view\views\SingleCheckbox;
use Pageflow\Core\view\views\TextField;
use Pageflow\Core\view\views\UploadField;

class FunctionalImageEditor extends Panel {

    private FunctionalImage $image;

    public function __construct(FunctionalImage $image) {
        parent::__construct($this->getTextResource('functional_images_editor_panel_title'), 'functional_image_editor');
        $this->image = $image;
    }

    public function getPanelContentTemplate(): string {
        return "images/templates/functional/image_editor.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $titleField    = new TextField('fimg_title',    $this->getTextResource('functional_images_title_label'),     $this->image->getTitle(),    true,  false, null);
        $altField      = new TextField('fimg_alt_text', $this->getTextResource('functional_images_alt_text_label'),  $this->image->getAltText(),  false, false, null);
        $publishedField = new SingleCheckbox('fimg_published', $this->getTextResource('functional_images_published_label'), $this->image->isPublished(), false, null);
        $uploadField   = new UploadField('fimg_file',   $this->getTextResource('functional_images_file_label'),      false, null);

        $data->assign('fimg_id',        $this->image->getId());
        $data->assign('title_field',    $titleField->render());
        $data->assign('alt_field',      $altField->render());
        $data->assign('published_field', $publishedField->render());
        $data->assign('upload_field',   $uploadField->render());

        $url = ($this->image->getFilename() && ImageUtility::exists($this->image->getFilename()))
            ? $this->image->getUrl()
            : null;
        $data->assign('preview_url',  $url);
        $data->assign('image_title',  $this->image->getTitle());
    }
}

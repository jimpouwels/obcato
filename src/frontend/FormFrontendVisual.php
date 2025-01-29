<?php

namespace Obcato\Core\frontend;

use Obcato\Core\database\dao\TemplateDao;
use Obcato\Core\database\dao\TemplateDaoMysql;
use Obcato\Core\frontend\handlers\FormStatus;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\pages\model\Page;
use Obcato\Core\modules\templates\model\Presentable;
use Obcato\Core\modules\webforms\model\Webform;
use Obcato\Core\modules\webforms\WebformItemFactory;
use const Obcato\core\FRONTEND_TEMPLATE_DIR;

class FormFrontendVisual extends FrontendVisual {

    private WebformItemFactory $webformItemFactory;

    private WebForm $webform;
    private TemplateDao $templateDao;

    public function __construct(Page $page, ?Article $article, WebForm $webform) {
        parent::__construct($page, $article);
        $this->webform = $webform;
        $this->webformItemFactory = WebformItemFactory::getInstance();
        $this->templateDao = TemplateDaoMysql::getInstance();
    }

    public function getTemplateFilename(): string {
        return FRONTEND_TEMPLATE_DIR . '/system/sa-form.tpl';
    }

    public function loadVisual(?array &$data): void {
        $this->assign('webform_id', $this->webform->getId());
        if ($this->webform->getIncludeCaptcha()) {
            $captchaKey = $this->webform->getCaptchaKey();
            $this->assign('captcha_key', $captchaKey);
        }
        $this->assign('title', $this->webform->getTitle());

        $webformChildData = $this->createChildData();
        $webformData = $this->renderWebForm($this->webform);
        $webformChildData->assign('webform', $webformData);
        $webformChildData->assign('has_captcha_error', FormStatus::getError("captcha"));
        $this->assign('form_html', $this->getTemplateEngine()->fetch(FRONTEND_TEMPLATE_DIR . "/" . $this->templateDao->getTemplateFile($this->webform->getTemplate()->getTemplateFileId())->getFileName(), $webformChildData));
    }

    public function getPresentable(): ?Presentable {
        return $this->webform;
    }

    private function renderWebForm(WebForm $webform): array {
        $webformData = array();
        $webformData['title'] = $webform->getTitle();
        $webformData['fields'] = $this->renderFields($webform);
        $webformData['is_submitted'] = FormStatus::getSubmittedForm() == $this->webform->getId();
        return $webformData;
    }

    private function renderFields(WebForm $webform): array {
        $fields = array();
        foreach ($webform->getFormFields() as $formField) {
            $field = $this->webformItemFactory->getFrontendVisualFor($webform, $formField, $this->getPage(), $this->getArticle());
            $fields[] = $field->render();
        }
        return $fields;
    }
}
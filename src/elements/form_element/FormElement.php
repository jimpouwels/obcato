<?php

namespace Obcato\Core\elements\form_element;

use Obcato\Core\core\model\Element;
use Obcato\Core\core\model\ElementMetadataProvider;
use Obcato\Core\database\dao\WebformDao;
use Obcato\Core\database\dao\WebformDaoMysql;
use Obcato\Core\database\MysqlConnector;
use Obcato\Core\elements\form_element\visuals\FormElementEditor;
use Obcato\Core\elements\form_element\visuals\FormElementStatics;
use Obcato\Core\frontend\ElementFrontendVisual;
use Obcato\Core\frontend\FormElementFrontendVisual;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\blocks\model\Block;
use Obcato\Core\modules\pages\model\Page;
use Obcato\Core\modules\webforms\model\Webform;
use Obcato\Core\request_handlers\HttpRequestHandler;
use Obcato\Core\view\TemplateEngine;
use Obcato\Core\view\views\ElementVisual;
use Obcato\Core\view\views\Visual;

class FormElement extends Element {

    private ?WebForm $webform = null;

    public function __construct(int $scopeId) {
        parent::__construct($scopeId, new FormElementMetadataProvider($this));
    }

    public function setWebForm(?WebForm $webform): void {
        $this->webform = $webform;
    }

    public function getWebForm(): ?WebForm {
        return $this->webform;
    }

    public function getStatics(): Visual {
        return new FormElementStatics();
    }

    public function getBackendVisual(): ElementVisual {
        return new FormElementEditor($this);
    }

    public function getFrontendVisual(Page $page, ?Article $article, ?Block $block = null): ElementFrontendVisual {
        return new FormElementFrontendVisual($page, $article, $block, $this);
    }

    public function getRequestHandler(): HttpRequestHandler {
        return new FormElementRequestHandler($this);
    }

    public function getSummaryText(): string {
        $summaryText = "";
        if ($this->webform) {
            $summaryText = $this->webform->getTitle();
        }
        return $summaryText;
    }
}


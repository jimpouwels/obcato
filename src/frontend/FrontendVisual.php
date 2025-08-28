<?php

namespace Obcato\Core\frontend;

use Obcato\Core\core\model\ElementHolder;
use Obcato\Core\database\dao\ElementDao;
use Obcato\Core\database\dao\ElementDaoMysql;
use Obcato\Core\frontend\helper\FontStyleHelper;
use Obcato\Core\frontend\helper\LinkHelper;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\articles\service\ArticleInteractor;
use Obcato\Core\modules\articles\service\ArticleService;
use Obcato\Core\modules\blocks\model\Block;
use Obcato\Core\modules\pages\model\Page;
use Obcato\Core\modules\templates\model\Presentable;
use Obcato\Core\modules\templates\model\TemplateVar;
use Obcato\Core\modules\templates\service\TemplateInteractor;
use Obcato\Core\modules\templates\service\TemplateService;
use Obcato\Core\utilities\Arrays;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\TemplateEngine;
use const Obcato\Core\FRONTEND_TEMPLATE_DIR;

abstract class FrontendVisual {

    private TemplateEngine $templateEngine;
    private TemplateData $templateData;
    private TemplateService $templateService;
    private LinkHelper $linkHelper;
    private ?Page $page;
    private ?Article $article;
    private ?Block $block;
    private ElementDao $elementDao;
    private ArticleService $articleService;

    public function __construct(?Page $page, ?Article $article, ?Block $block = null) {
        $this->linkHelper = LinkHelper::getInstance($page, $article);
        $this->page = $page;
        $this->article = $article;
        $this->block = $block;
        $this->templateEngine = TemplateEngine::getInstance();
        $this->templateData = $this->createChildData();
        $this->templateService = TemplateInteractor::getInstance();
        $this->elementDao = ElementDaoMysql::getInstance();
        $this->articleService = ArticleInteractor::getInstance();
    }

    public function render(array &$parentData = array()): string {
        $this->load($parentData);
        $html = $this->templateEngine->fetch($this->getTemplateFilename(), $this->templateData);
        return $this->replaceTemplateIncludes($html);
    }

    public function load(array &$parentData): void {
        $presentable = $this->getPresentable();
        $templateVars = array();

        if ($presentable && $presentable->getTemplate()) {
            $templateVarDefs = $this->templateService->getTemplateVarDefsByTemplate($presentable->getTemplate());
            foreach ($presentable->getTemplate()->getTemplateVars() as $templateVar) {
                $varValue = $templateVar->getValue() ?: $this->getDefaultValueFor($templateVar, $templateVarDefs);
                if (is_numeric($varValue)) {
                    $varValue = intval($varValue);
                }
                $templateVars[$templateVar->getName()] = $varValue;
            }
        }
        $this->assign("var", $templateVars);
        $this->loadVisual($parentData);
        if ($parentData) {
            foreach ($parentData as $key => $value) {
                $this->assign($key, $value);
            }
        }
    }

    abstract function loadVisual(array &$data): void;

    abstract function getPresentable(): ?Presentable;

    abstract function getTemplateFilename(): string;

    protected function getTemplateEngine(): TemplateEngine {
        return $this->templateEngine;
    }

    protected function getLinkHelper(): LinkHelper {
        return $this->linkHelper;
    }

    protected function renderElementHolderContent(ElementHolder $elementHolder, ?array &$data): void {
        $elementGroups = array();
        $elementGroup = array();

        $previousSeparator = null;
        foreach ($elementHolder->getElements() as $element) {
            $elementType = $this->elementDao->getElementTypeForElement($element->getId())->getIdentifier();
            if ($elementType == 'separator_element') {
                $elementGroups[] = $elementGroup;
                $elementGroup = array();
            }
            $elementData = array();
            $elementData["type"] = $elementType;
            $elementData["template"] = $element->getTemplate()?->getName();
            if ($elementType == 'separator_element') {
                $elementData["is_closing"] = false;
            }
            if ($previousSeparator && $elementType == 'separator_element') {
                $previousVisual = $previousSeparator->getFrontendVisual($this->getPage(), $this->getArticle(), $this->getBlock());
                $closePrevious = array();
                $closePrevious['is_closing'] = true;
                $elementData["close_previous_separator"] = $previousVisual->render($closePrevious);
                $previousSeparator = null;
            }
            if ($element->getTemplate()) {
                $elementVisual = $element->getFrontendVisual($this->getPage(), $this->getArticle(), $this->getBlock());
                $elementData["to_string"] = $elementVisual->render($elementData);
            } else {
                $elementData["to_string"] = "";
                if (isset($elementData["close_previous_separator"])) {
                    $elementData["to_string"] = $elementData["close_previous_separator"];
                }
            }
            $elementGroup[] = $elementData;
            if ($elementType == 'separator_element') {
                $previousSeparator = $element;
            }
        }
        if ($previousSeparator && $previousSeparator->getTemplate()) {
            $separatorData = array();
            $separatorData['is_closing'] = true;

            $elementData = array();
            $elementData["type"] = $this->elementDao->getElementTypeForElement($previousSeparator->getId())->getIdentifier();;
            $elementData["template"] = $previousSeparator->getTemplate()?->getName();
            $previousVisual = $previousSeparator->getFrontendVisual($this->getPage(), $this->getArticle(), $this->getBlock());
            $elementData["to_string"] = $previousVisual->render($separatorData);
            $elementGroup[] = $elementData;
        }
        $elementGroups[] = $elementGroup;
        $data['element_groups'] = $elementGroups;
    }

    protected function createChildData(): TemplateData {
        return $this->templateEngine->createChildData();
    }

    protected function assign(string $key, mixed $value): void {
        $this->templateData->assign($key, $value);
    }

    protected function assignGlobal(string $key, mixed $value): void {
        $this->templateEngine->assign($key, $value);
    }

    protected function fetch(string $templateFilename, ?TemplateData $templateData = null): string {
        return $this->templateEngine->fetch($templateFilename, $templateData == null ? $this->templateData : $templateData);
    }

    protected function toHtml(?string $value, ElementHolder $elementHolder): string {
        if (!$value) {
            return "";
        }
        $value = nl2br($value);
        $value = $this->replaceSmartyQuery($value);
        $value = FontStyleHelper::createColors($value);
        $value = FontStyleHelper::createItalic($value);
        $value = FontStyleHelper::createBold($value);
        $value = $this->replaceArticleMetadataReferences($value);
        return $this->linkHelper->createLinksInString($value, $elementHolder);
    }

    protected function getPage(): Page {
        return $this->page;
    }

    protected function getBlock(): ?Block {
        return $this->block;
    }

    protected function getArticle(): ?Article {
        return $this->article;
    }

    protected function getElementHolder(): ElementHolder {
        if ($this->block) {
            return $this->block;
        }
        return !is_null($this->article) ? $this->article : $this->page;
    }

    protected function toAnchorValue(string $value): string {
        $anchorValue = strtolower($value);
        $anchorValue = str_replace("-", " ", $anchorValue);
        $anchorValue = str_replace("  ", " ", $anchorValue);
        $anchorValue = str_replace(" ", "-", $anchorValue);
        $anchorValue = str_replace("--", "-", $anchorValue);
        return urlencode($anchorValue);
    }

    private function replaceTemplateIncludes(string $html): string {
        $matches = null;
        preg_match_all('/<include template="(.*)".*\/>/', $html, $matches);
        for ($i = 0; $i < count($matches[1]); $i++) {
            $templateFile = $matches[1][$i];
            $templateContents = file_get_contents(FRONTEND_TEMPLATE_DIR . "/" . $templateFile);
            if (str_ends_with($templateFile, '.js')) {
                $templateContents = '{literal}' . $templateContents . '{/literal}';
            }
            $includeHtml = $this->templateEngine->fetch('string:' . $templateContents, $this->templateData);
            $html = str_replace($matches[0][$i], $includeHtml, $html);
            $html = $this->replaceTemplateIncludes($html);
        }
        return $html;
    }

    private function getDefaultValueFor(TemplateVar $templateVar, array $templateVarDefs): string {
        $value = Arrays::firstMatch($templateVarDefs, fn($item) => $templateVar->getName() == $item->getName())->getDefaultValue();
        return $value ?: "";
    }

    private function replaceSmartyQuery(string $text): string {
        $matches = array();
        preg_match_all('/\{\$[^ ]*?}/', $text, $matches);

        for ($i = 0; $i < count($matches[0]); $i++) {
            $placeholder = $matches[0][$i];
            $globalValueQuery = str_replace('{', '', $placeholder);
            $globalValueQuery = str_replace('}', '', $globalValueQuery);
            $globalValueQuery = str_replace('$', '', $globalValueQuery);
            $parts = explode('.', $globalValueQuery);

            $currentPos = null;
            while (count($parts) > 0) {
                $part = array_shift($parts);
                if (!$currentPos) {
                    $currentPos = $this->getTemplateEngine()->getGlobal($part);
                } else {
                    $currentPos = $currentPos[$part];
                }
            }
            $text = str_replace($placeholder, $currentPos, $text);
        }
        return $text;
    }

    private function replaceArticleMetadataReferences($value): string {
        if ($this->article) {
            preg_match_all('/\$([A-Za-z_.]*)/', $value, $matches);
            $metadataFields = $this->articleService->getMetadataFields();
            for ($i = 0; $i < count($matches[0]); $i++) {
                foreach ($metadataFields as $field) {
                    if ($matches[1][$i] == $field->getName()) {
                        $fieldValue = $this->articleService->getMetadataFieldValue($this->article, $field)->getValue() ?: $field->getDefaultValue();
                        $value = str_replace($matches[0][$i], $fieldValue, $value);
                    } else if (explode('.', $matches[1][$i])[0] == "parent_article" && explode('.', $matches[1][$i])[1] == $field->getName()) {
                        $fieldValue = $this->articleService->getMetadataFieldValue($this->articleService->getArticle($this->article->getParentArticleId()), $field)->getValue() ?: $field->getDefaultValue();
                        $value = str_replace($matches[0][$i], $fieldValue, $value);
                    }
                }
            }
        }
        return $value;
    }
}
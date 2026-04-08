<?php

namespace Obcato\Core\frontend;

use Obcato\Core\core\model\ElementHolder;
use Obcato\Core\database\dao\ElementDao;
use Obcato\Core\database\dao\ElementDaoMysql;
use Obcato\Core\database\dao\LinkDao;
use Obcato\Core\database\dao\LinkDaoMysql;
use Obcato\Core\elements\list_element\ListElement;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\blocks\model\Block;
use Obcato\Core\modules\pages\model\Page;

class ListElementFrontendVisual extends ElementFrontendVisual {

    private ElementDao $elementDao;
    private LinkDao $linkDao;

    public function __construct(Page $page, ?Article $article, ?Block $block, ListElement $listElement) {
        parent::__construct($page, $article, $block, $listElement);
        $this->elementDao = ElementDaoMysql::getInstance();
        $this->linkDao = LinkDaoMysql::getInstance();
    }

    public function loadElement(array &$data): void {
        $data["title"] = $this->toHtml($this->getElement()->getTitle(), $this->getElementHolder());
        $data["items"] = $this->renderListItems($this->getElementHolder());
    }

    private function renderListItems(ElementHolder $element_holder): array {
        $listItems = array();
        $needsSave = false;
        
        foreach ($this->getElement()->getListItems() as $listItem) {
            $text = $listItem->getText();
            $originalText = $text;
            
            // Migrate legacy markdown and link codes
            $text = $this->migrateLegacyMarkdown($text);
            $text = $this->migrateLegacyLinkCodes($text);
            
            // If text was migrated, save it back
            if ($text !== $originalText) {
                $listItem->setText($text);
                $needsSave = true;
            }
            
            $listItems[] = $this->toHtml($text, $element_holder);
        }
        
        // Save all list items if any were migrated
        if ($needsSave) {
            $this->elementDao->updateElement($this->getElement());
        }
        
        return $listItems;
    }
    
    private function migrateLegacyMarkdown(?string $text): ?string {
        if (!$text) {
            return $text;
        }
        
        // Check if text contains legacy markdown
        $hasLegacyMarkdown = preg_match('/\*\*.*?\*\*|__.*?__|\[([^\]]+)\]\(([^\)]+)\)/', $text);
        
        if (!$hasLegacyMarkdown) {
            return $text;
        }
        
        // Convert **text** to <strong>text</strong>
        $text = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text);
        
        // Convert __text__ to <em>text</em>
        $text = preg_replace('/__(.*?)__/', '<em>$1</em>', $text);
        
        // Convert [linktext](url) to <a href="url" target="_blank" data-link-type="url" data-link-target="external" class="external">linktext</a>
        $text = preg_replace_callback('/\[([^\]]+)\]\(([^\)]+)\)/', function($matches) {
            $linkText = $matches[1];
            $url = $matches[2];
            
            // Add https:// if no protocol and not a relative path
            if (!preg_match('/^https?:\/\//i', $url) && !preg_match('/^\//', $url)) {
                $url = 'https://' . $url;
            }
            
            return '<a href="' . htmlspecialchars($url, ENT_QUOTES) . '" target="_blank" data-link-type="url" data-link-target="external" class="external">' . $linkText . '</a>';
        }, $text);
        
        // Convert newlines to <br> tags (important: do this AFTER link conversion to avoid breaking URLs)
        $text = nl2br($text);
        
        return $text;
    }
    
    private function migrateLegacyLinkCodes(?string $text): ?string {
        if (!$text) {
            return $text;
        }
        
        // Check if text contains legacy link codes
        if (strpos($text, '[LINK C="') === false) {
            return $text;
        }
        
        try {
            // Get all links for this element holder
            $elementHolder = $this->getElementHolder();
            $links = $this->linkDao->getLinksForElementHolder($elementHolder->getId());
            
            // Create a map of code -> link for quick lookup
            $linkMap = [];
            foreach ($links as $link) {
                if ($link->getCode()) {
                    $linkMap[$link->getCode()] = $link;
                }
            }
            
            // Find all link code tags in the text
            $pattern = '/\[LINK C="([^"]+)"\](.*?)\[\/LINK\]/is';
            $matches = [];
            preg_match_all($pattern, $text, $matches, PREG_SET_ORDER);
            
            // Replace each link code tag with HTML
            $migratedText = $text;
            foreach ($matches as $match) {
                $fullMatch = $match[0];
                $linkCode = $match[1];
                $linkText = $match[2];
                
                // Find the corresponding Link object
                if (!isset($linkMap[$linkCode])) {
                    continue;
                }
                
                $link = $linkMap[$linkCode];
                
                // Simple conversion - target based on whether it has targetElementHolder
                $isExternal = !$link->getTargetElementHolderId();
                $target = $isExternal ? 'external' : 'internal';
                
                // Create basic HTML link
                $replacement = '<a href="#" data-link-type="url" data-link-target="' . $target . '">' . $linkText . '</a>';
                
                $migratedText = str_replace($fullMatch, $replacement, $migratedText);
            }
            
            return $migratedText;
            
        } catch (\Exception $e) {
            error_log("List item link code migration failed: " . $e->getMessage());
            return $text;
        }
    }
}
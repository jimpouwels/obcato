<?php

namespace Obcato\Core\frontend;

use Obcato\Core\core\model\ElementHolder;
use Obcato\Core\core\model\Link;
use Obcato\Core\database\dao\ElementDao;
use Obcato\Core\database\dao\ElementDaoMysql;
use Obcato\Core\database\dao\LinkDao;
use Obcato\Core\database\dao\LinkDaoMysql;
use Obcato\Core\elements\text_element\TextElement;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\blocks\model\Block;
use Obcato\Core\modules\pages\model\Page;

class TextElementFrontendVisual extends ElementFrontendVisual {

    private ElementDao $elementDao;
    private LinkDao $linkDao;

    public function __construct(Page $page, ?Article $article, ?Block $block, TextElement $element) {
        parent::__construct($page, $article, $block, $element);
        $this->elementDao = ElementDaoMysql::getInstance();
        $this->linkDao = LinkDaoMysql::getInstance();
    }

    public function loadElement(array &$data): void {
        $text = $this->getElement()->getText();
        $originalText = $text;
        
        // Migrate legacy markdown syntax
        $text = $this->migrateLegacyMarkdown($text);
        
        // Migrate legacy link code syntax
        $text = $this->migrateLegacyLinkCodes($text);
        
        // If text was migrated, save it back to database
        if ($text !== $originalText) {
            $this->getElement()->setText($text);
            $this->elementDao->updateElement($this->getElement());
        }
        
        $data["title"] = $this->toHtml($this->getElement()->getTitle(), $this->getElementHolder());
        $data["text"] = $this->toHtml($this->getElement()->getText(), $this->getElementHolder());
        $data["text_wysiwyg"] = $this->getElement()->getText();
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
                    // Link not found in database, skip migration for this tag
                    continue;
                }
                
                $link = $linkMap[$linkCode];
                $replacement = $this->convertLinkToHtml($link, $linkText);
                
                // Only replace if conversion was successful
                if ($replacement !== null) {
                    $migratedText = str_replace($fullMatch, $replacement, $migratedText);
                }
            }
            
            return $migratedText;
            
        } catch (\Exception $e) {
            // If anything goes wrong, return original text to avoid breaking content
            error_log("Link code migration failed: " . $e->getMessage());
            return $text;
        }
    }
    
    private function convertLinkToHtml(Link $link, string $linkText): ?string {
        try {
            $attributes = [];
            
            // Determine link type and target
            if ($link->getTargetElementHolderId()) {
                // Internal link to a page or article
                $targetElementHolder = $link->getTargetElementHolder();
                
                if (!$targetElementHolder) {
                    // Target element holder not found, use external URL fallback if available
                    if ($link->getTargetAddress()) {
                        $attributes[] = 'href="' . htmlspecialchars($link->getTargetAddress(), ENT_QUOTES) . '"';
                        $attributes[] = 'data-link-type="url"';
                        $attributes[] = 'data-link-target="external"';
                    } else {
                        return null;
                    }
                } else {
                    // Determine if it's a page or article
                    $holderType = $targetElementHolder->getType();
                    
                    if ($holderType === Page::ElementHolderType) {
                        $attributes[] = 'data-link-type="page"';
                        $attributes[] = 'data-link-id="' . $targetElementHolder->getId() . '"';
                        // Pages can also be external or internal based on the target attribute
                        $target = $link->getTarget();
                        // _blank or [popup] = external, _self or null = internal
                        $isExternal = ($target === '_blank' || $target === '[popup]');
                        $attributes[] = 'data-link-target="' . ($isExternal ? 'external' : 'internal') . '"';
                    } elseif ($holderType === Article::ElementHolderType) {
                        $attributes[] = 'data-link-type="article"';
                        $attributes[] = 'data-link-id="' . $targetElementHolder->getId() . '"';
                        // Articles can be external or internal based on the target attribute
                        $target = $link->getTarget();
                        // _blank or [popup] = external, _self or null = internal
                        $isExternal = ($target === '_blank' || $target === '[popup]');
                        $attributes[] = 'data-link-target="' . ($isExternal ? 'external' : 'internal') . '"';
                    } else {
                        // Unknown type, fallback to URL if available
                        if ($link->getTargetAddress()) {
                            $attributes[] = 'href="' . htmlspecialchars($link->getTargetAddress(), ENT_QUOTES) . '"';
                            $attributes[] = 'data-link-type="url"';
                            $attributes[] = 'data-link-target="external"';
                        } else {
                            return null;
                        }
                    }
                }
            } else {
                // External URL link
                $targetUrl = $link->getTargetAddress();
                if (!$targetUrl) {
                    return null;
                }
                
                $attributes[] = 'href="' . htmlspecialchars($targetUrl, ENT_QUOTES) . '"';
                $attributes[] = 'data-link-type="url"';
                $attributes[] = 'data-link-target="external"';
            }
            
            // Build the HTML anchor tag
            $html = '<a ' . implode(' ', $attributes) . '>' . $linkText . '</a>';
            
            return $html;
            
        } catch (\Exception $e) {
            error_log("Failed to convert link to HTML: " . $e->getMessage());
            return null;
        }
    }
}

// Rich Text Editor functionality
var currentLinkElement = null;
var currentEditor = null;
var savedRange = null;
var linkSearchTimeout = null;
var preserveEditorFocusState = false;

// Cached DOM selectors
var $dialog, $textInput, $urlInput, $newTabCheckbox, $deleteBtn, $dialogTitle;
var $urlSelected, $pageSelected, $articleSelected;
var $pageSearch, $articleSearch, $pageResults, $articleResults;
var $urlInputGroup;

$(document).ready(function() {
    // Cache selectors once DOM is ready
    $dialog = $('#link-editor-dialog');
    $textInput = $('#link-editor-text');
    $urlInput = $('#link-editor-url');
    $newTabCheckbox = $('#link-new-tab');
    $deleteBtn = $('#link-editor-delete');
    $dialogTitle = $('#link-editor-title');
    $urlSelected = $('#link-url-selected');
    $pageSelected = $('#link-page-selected');
    $articleSelected = $('#link-article-selected');
    $pageSearch = $('#link-page-search');
    $articleSearch = $('#link-article-search');
    $pageResults = $('#link-page-results');
    $articleResults = $('#link-article-results');
    $urlInputGroup = $('#link-url-input-group');
    
    initRichTextEditors();
    initLinkEditorDialog();
    initFormSubmitHandler();
});

function initFormSubmitHandler() {
    // Sync all rich text editors before form submission
    $('form').on('submit', function() {
        $('.rich-text-editor-wrapper').each(function() {
            syncToHiddenField($(this));
        });
    });
}

function initRichTextEditors() {
    // Click outside a focused editor wrapper should close editor focus state.
    $(document).off('mousedown.richTextEditorBlur').on('mousedown.richTextEditorBlur', function(e) {
        // Keep focus state while interacting with the link modal.
        if ($dialog && $dialog.length > 0 && $dialog.is(':visible') && $(e.target).closest('#link-editor-dialog').length > 0) {
            return;
        }

        $('.rich-text-editor-wrapper.focused').each(function() {
            if ($(e.target).closest(this).length === 0) {
                const wrapper = $(this);
                wrapper.find('.rich-text-content').blur();
                wrapper.removeClass('focused');
            }
        });
    });

    // Handle focus
    $('.rich-text-content').on('focus', function() {
        $(this).closest('.rich-text-editor-wrapper').addClass('focused');
    });
    
    // Handle blur
    $('.rich-text-content').on('blur', function() {
        if (preserveEditorFocusState) {
            return;
        }

        const wrapper = $(this).closest('.rich-text-editor-wrapper');
        // Delay so toolbar/button interactions can complete first.
        setTimeout(function() {
            if (wrapper.find(':focus').length === 0) {
                wrapper.removeClass('focused');
            }
        }, 0);
    });
    
    // Allow clicking to focus
    $('.rich-text-content').on('click', function() {
        if (!$(this).attr('contenteditable') || $(this).attr('contenteditable') === 'false') {
            $(this).focus();
        }
    });
    
    // Prevent toolbar buttons from stealing focus from the contenteditable editor.
    $('.rich-text-btn').on('mousedown', function(e) {
        e.preventDefault();
    });

    // Handle toolbar button clicks
    $('.rich-text-btn').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const command = $(this).data('command');
        const wrapper = $(this).closest('.rich-text-editor-wrapper');
        const editor = wrapper.find('.rich-text-content');
        
        // Focus editor first
        editor.focus();
        
        if (command === 'createLink') {
            createLink(editor);
        } else {
            document.execCommand(command, false, null);
        }
        
        updateActiveButtons(editor);
        syncToHiddenField(wrapper);
        
        return false;
    });
    
    // Handle click on links to edit them
    $('.rich-text-content').on('click', 'a', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const editor = $(this).closest('.rich-text-content');
        editLink($(this), editor);
        return false;
    });
    
    // Update hidden textarea on content change
    $('.rich-text-content').on('input', function() {
        syncToHiddenField(getWrapper($(this)));
    });

    // Strip formatting on paste — insert plain text only
    $('.rich-text-content').on('paste', function(e) {
        e.preventDefault();
        var text = (e.originalEvent || e).clipboardData.getData('text/plain');
        document.execCommand('insertText', false, text);
    });
    
    // Handle keyboard shortcuts and prevent typing in links
    $('.rich-text-content').on('keydown', function(e) {
        // Handle Enter key - insert <br> instead of <div>
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();

            const selection = window.getSelection();
            if (!selection.rangeCount) return false;

            const range = selection.getRangeAt(0);
            if (!range.collapsed) range.deleteContents();

            const editor = e.currentTarget;
            const inlineTags = new Set(['STRONG', 'B', 'EM', 'I', 'U', 'A', 'SPAN']);

            // Returns true if there's no meaningful content after `afterNode` within `parentEl`
            function isAtEnd(afterNode, parentEl) {
                let sib = afterNode.nextSibling;
                while (sib) {
                    if (sib.nodeType === Node.TEXT_NODE && sib.textContent.trim() !== '') return false;
                    if (sib.nodeType === Node.ELEMENT_NODE && sib.tagName !== 'BR') return false;
                    sib = sib.nextSibling;
                }
                return true;
            }

            // Insert BR at cursor
            const br = document.createElement('br');
            range.insertNode(br);

            // Bug 2: if BR landed at the end of an inline formatting ancestor, escape it.
            // Walk up the tree; track the outermost inline ancestor where cursor is at end.
            let escapeTarget = null;
            let walkNode = br;
            let walkParent = br.parentNode;
            while (walkParent && walkParent !== editor) {
                if (inlineTags.has(walkParent.tagName) && isAtEnd(walkNode, walkParent)) {
                    escapeTarget = walkParent;
                }
                walkNode = walkParent;
                walkParent = walkParent.parentNode;
            }

            // Bug 1: find the editor-level node to use for sentinel check.
            // If we're escaping a formatting element, that element is at (or near) editor level.
            const topNode = escapeTarget ? escapeTarget : (function() {
                let n = br;
                while (n.parentNode && n.parentNode !== editor) n = n.parentNode;
                return n;
            })();

            // Add sentinel BR if nothing (visible) follows at editor level,
            // but only if there isn't already a BR there (left from previous Enter).
            const topNodeNext = topNode.nextSibling;
            const alreadyHasSentinel = topNodeNext && topNodeNext.nodeType === Node.ELEMENT_NODE && topNodeNext.tagName === 'BR';
            if (isAtEnd(topNode, editor) && !alreadyHasSentinel) {
                const sentinel = document.createElement('br');
                topNode.parentNode.insertBefore(sentinel, topNode.nextSibling);
            }

            // Position cursor
            const newRange = document.createRange();
            if (escapeTarget) {
                newRange.setStartAfter(escapeTarget);
            } else {
                newRange.setStartAfter(br);
            }
            newRange.collapse(true);
            selection.removeAllRanges();
            selection.addRange(newRange);

            $(this).trigger('input');
            return false;
        }
        // Ctrl+Z / Cmd+Z for undo
        if ((e.ctrlKey || e.metaKey) && e.key === 'z') {
            e.preventDefault();
            document.execCommand('undo', false, null);
            syncToHiddenField(getWrapper($(this)));
            return false;
        }
        // Ctrl+B for bold
        if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
            e.preventDefault();
            document.execCommand('bold', false, null);
            updateActiveButtons($(this));
            syncToHiddenField(getWrapper($(this)));
            return false;
        }
        // Ctrl+I for italic
        else if ((e.ctrlKey || e.metaKey) && e.key === 'i') {
            e.preventDefault();
            document.execCommand('italic', false, null);
            updateActiveButtons($(this));
            syncToHiddenField(getWrapper($(this)));
            return false;
        }
        // Ctrl+U for underline
        else if ((e.ctrlKey || e.metaKey) && e.key === 'u') {
            e.preventDefault();
            document.execCommand('underline', false, null);
            updateActiveButtons($(this));
            syncToHiddenField(getWrapper($(this)));
            return false;
        }
    });
    
    // Update active button states on selection change
    $('.rich-text-content').on('mouseup keyup', function() {
        updateActiveButtons($(this));
    });
}

function syncToHiddenField(wrapper) {
    const editor = wrapper.find('.rich-text-content');
    const hiddenTextarea = wrapper.find('textarea');
    const htmlContent = editor.html();
    
    hiddenTextarea.val(htmlContent);
}

function getWrapper($editor) {
    return $editor.closest('.rich-text-editor-wrapper');
}

function updateActiveButtons(editor) {
    const toolbar = getWrapper(editor).find('.rich-text-toolbar');
    
    toolbar.find('.rich-text-btn').removeClass('active');
    
    if (document.queryCommandState('bold')) {
        toolbar.find('[data-command="bold"]').addClass('active');
    }
    if (document.queryCommandState('italic')) {
        toolbar.find('[data-command="italic"]').addClass('active');
    }
    if (document.queryCommandState('underline')) {
        toolbar.find('[data-command="underline"]').addClass('active');
    }
}

function createLink(editor) {
    const selection = window.getSelection();
    const selectedText = selection.toString();
    
    // Save the current selection range before dialog opens
    if (selection.rangeCount > 0) {
        savedRange = selection.getRangeAt(0).cloneRange();
    } else {
        savedRange = null;
    }
    
    currentEditor = editor;
    currentLinkElement = null;
    
    // Reset form
    $textInput.val(selectedText || '');
    $urlInput.val('');
    $newTabCheckbox.prop('checked', true);
    $deleteBtn.hide();
    $dialogTitle.text('Link toevoegen');
    
    // Reset to URL tab and clear all selections
    switchLinkTab('url');
    clearUrl();
    clearPage();
    clearArticle();
    
    showLinkEditorDialog();
}

function editLink(linkElement, editor) {
    currentEditor = editor;
    currentLinkElement = linkElement;
    savedRange = null;
    
    $textInput.val(linkElement.text());
    $deleteBtn.show();
    $dialogTitle.text('Link bewerken');
    
    const linkTarget = linkElement.attr('data-link-target') || 'external';
    $newTabCheckbox.prop('checked', linkTarget === 'external');
    
    // Determine link type and load appropriate data
    const linkType = linkElement.attr('data-link-type') || 'url';
    const linkId = linkElement.attr('data-link-id');
    const linkUrl = linkElement.attr('data-link-url') || linkElement.attr('href'); // Support both old href and new data-link-url
    
    // Clear all selections first
    clearUrl();
    clearPage();
    clearArticle();
    
    // Switch to appropriate tab
    switchLinkTab(linkType);
    
    if (linkType === 'url' && linkUrl && linkUrl !== '#') {
        // Show URL as selected
        selectUrl(linkUrl);
    } else if (linkType === 'page' && linkId) {
        // Show page placeholder, then fetch real title
        $pageSelected.show().find('.selected-item-name').text(linkElement.text());
        $pageSelected.data('page-id', linkId);
        $pageSearch.val('').closest('.form-field-group').hide();
        
        // Fetch actual page title
        $.ajax({
            url: '/admin/api/page/get?id=' + linkId,
            method: 'GET',
            success: function(page) {
                if (page && page.title) {
                    $pageSelected.find('.selected-item-name').text(page.title);
                }
            }
        });
    } else if (linkType === 'article' && linkId) {
        // Show article placeholder, then fetch real title
        $articleSelected.show().find('.selected-item-name').text(linkElement.text());
        $articleSelected.data('article-id', linkId);
        $articleSearch.val('').closest('.form-field-group').hide();
        
        // Fetch actual article title
        $.ajax({
            url: '/admin/api/article/get?id=' + linkId,
            method: 'GET',
            success: function(article) {
                if (article && article.title) {
                    $articleSelected.find('.selected-item-name').text(article.title);
                }
            }
        });
    }
    
    showLinkEditorDialog();
}

function initLinkEditorDialog() {
    // Tab switching
    $('.link-type-tab').on('click', function() {
        const tab = $(this).data('tab');
        switchLinkTab(tab);
    });
    
    // Page search
    $pageSearch.on('input', function() {
        const keyword = $(this).val().trim();
        clearTimeout(linkSearchTimeout);
        
        if (keyword.length < 2) {
            $pageResults.empty();
            return;
        }
        
        linkSearchTimeout = setTimeout(function() {
            searchPages(keyword);
        }, 300);
    });
    
    // Article search
    $articleSearch.on('input', function() {
        const keyword = $(this).val().trim();
        clearTimeout(linkSearchTimeout);
        
        if (keyword.length < 2) {
            $articleResults.empty();
            return;
        }
        
        linkSearchTimeout = setTimeout(function() {
            searchArticles(keyword);
        }, 300);
    });
    
    // URL add button
    $('#link-url-add').on('click', function() {
        const url = $urlInput.val().trim();
        if (url) {
            selectUrl(url);
        }
    });
    
    // Clear URL selection
    $urlSelected.find('.clear-selection').on('click', function() {
        clearUrl();
    });
    
    // Clear page selection
    $pageSelected.find('.clear-selection').on('click', function() {
        clearPage();
    });
    
    // Clear article selection
    $articleSelected.find('.clear-selection').on('click', function() {
        clearArticle();
    });
    
    // Save button
    $('#link-editor-save').on('click', function() {
        saveLinkFromDialog();
    });
    
    // Delete button
    $('#link-editor-delete').on('click', function() {
        if (currentLinkElement) {
            const text = currentLinkElement.text();
            currentLinkElement.replaceWith(text);
            
            hideLinkEditorDialog();
            
            if (currentEditor) {
                syncToHiddenField(getWrapper(currentEditor));
            }
        }
    });
    
    // Cancel button
    $('#link-editor-cancel').on('click', function() {
        hideLinkEditorDialog(true);
    });
    
    // Close on backdrop click
    $('.link-editor-backdrop').on('click', function() {
        hideLinkEditorDialog(true);
    });
    
    // Close on ESC key
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $dialog.is(':visible')) {
            hideLinkEditorDialog(true);
        }
    });
}

function switchLinkTab(tab) {
    // Update tab buttons
    $('.link-type-tab').removeClass('active');
    $('.link-type-tab[data-tab="' + tab + '"]').addClass('active');
    
    // Show correct content
    $('.link-type-content').hide();
    $('#link-type-' + tab).show();
    
    // Show search inputs if nothing is selected
    if (tab === 'page' && !$pageSelected.is(':visible')) {
        $pageSearch.closest('.form-field-group').show();
    }
    if (tab === 'article' && !$articleSelected.is(':visible')) {
        $articleSearch.closest('.form-field-group').show();
    }

    // Auto-focus the input field if nothing is selected on this tab
    setTimeout(function() {
        if (tab === 'url' && !$urlSelected.is(':visible')) {
            $urlInput.focus();
        } else if (tab === 'page' && !$pageSelected.is(':visible')) {
            $pageSearch.focus();
        } else if (tab === 'article' && !$articleSelected.is(':visible')) {
            $articleSearch.focus();
        }
    }, 50);
}

function searchPages(keyword) {
    $pageResults.html('<div class="ajax-spinner"><span></span></div>');
    $.ajax({
        url: '/admin/api/page/search?keyword=' + encodeURIComponent(keyword),
        method: 'GET',
        success: function(pages) {
            displayPageResults(pages);
        },
        error: function() {
            $pageResults.html('<div class="link-search-error">Zoeken mislukt</div>');
        }
    });
}

function searchArticles(keyword) {
    $articleResults.html('<div class="ajax-spinner"><span></span></div>');
    $.ajax({
        url: '/admin/api/article/search?keyword=' + encodeURIComponent(keyword),
        method: 'GET',
        success: function(articles) {
            displayArticleResults(articles);
        },
        error: function() {
            $articleResults.html('<div class="link-search-error">Zoeken mislukt</div>');
        }
    });
}

function displayPageResults(pages) {
    $pageResults.empty();
    
    if (pages.length === 0) {
        $pageResults.html('<div class="link-search-no-results">Geen pagina\'s gevonden</div>');
        return;
    }
    
    pages.forEach(function(page) {
        const $item = $('<div class="link-search-result-item"></div>');
        $item.html('<div class="result-title">' + page.title + '</div><div class="result-path">' + page.path + '</div>');
        $item.data('page-id', page.id);
        $item.data('page-title', page.title);
        $item.on('click', function() {
            selectPage(page.id, page.title);
        });
        $pageResults.append($item);
    });
}

function displayArticleResults(articles) {
    $articleResults.empty();
    
    if (articles.length === 0) {
        $articleResults.html('<div class="link-search-no-results">Geen artikelen gevonden</div>');
        return;
    }
    
    articles.forEach(function(article) {
        const $item = $('<div class="link-search-result-item"></div>');
        $item.html('<div class="result-title">' + article.title + '</div>' + 
                   (article.intro ? '<div class="result-path">' + article.intro + '</div>' : ''));
        $item.data('article-id', article.id);
        $item.data('article-title', article.title);
        $item.on('click', function() {
            selectArticle(article.id, article.title);
        });
        $articleResults.append($item);
    });
}

function selectUrl(url) {
    // Clear other selections (mutual exclusion)
    clearPage();
    clearArticle();

    
    // Show URL as selected
    $urlSelected.show().find('.selected-item-name').text(url);
    $urlSelected.data('url', url);
    $urlInputGroup.hide();
}

function selectPage(pageId, pageTitle) {
    // Clear other selections (mutual exclusion)
    clearUrl();
    clearArticle();
    
    // Show page selection
    $pageSelected.show().find('.selected-item-name').text(pageTitle);
    $pageSelected.data('page-id', pageId);
    $pageResults.empty();
    $pageSearch.val('').closest('.form-field-group').hide();
}

function selectArticle(articleId, articleTitle) {
    // Clear other selections (mutual exclusion)
    clearUrl();
    clearPage();
    
    // Show article selection
    $articleSelected.show().find('.selected-item-name').text(articleTitle);
    $articleSelected.data('article-id', articleId);
    $articleResults.empty();
    $articleSearch.val('').closest('.form-field-group').hide();
}

function clearUrl() {
    $urlSelected.hide().data('url', null);
    $urlInput.val();
    $urlInputGroup.show();
}

function clearPage() {
    $pageSelected.hide().data('page-id', null);
    $pageSearch.val('').closest('.form-field-group').show();
    $pageResults.empty();
}

function clearArticle() {
    $articleSelected.hide().data('article-id', null);
    $articleSearch.val('').closest('.form-field-group').show();
    $articleResults.empty();
}

function saveLinkFromDialog() {
    const text = $textInput.val().trim();
    const newTab = $newTabCheckbox.is(':checked');
    const linkTarget = newTab ? 'external' : 'internal';
    
    if (!text) {
        return;
    }
    
    // Check which item is selected (only one can be selected at a time)
    const url = $urlSelected.data('url');
    const pageId = $pageSelected.data('page-id');
    const articleId = $articleSelected.data('article-id');
    
    // Exactly one must be selected
    if (!url && !pageId && !articleId) {
        alert('Selecteer een pagina, artikel of voer een URL in');
        return;
    }
    
    let linkData = {
        text: text,
        target: linkTarget
    };
    
    // Set data based on what's selected
    if (url) {
        linkData.type = 'url';
        linkData.url = url;
    } else if (pageId) {
        linkData.type = 'page';
        linkData.id = pageId;
    } else if (articleId) {
        linkData.type = 'article';
        linkData.id = articleId;
    }
    
    if (currentLinkElement) {
        // Edit existing link
        updateLinkElement(currentLinkElement, linkData);
    } else {
        // Create new link
        createLinkElement(linkData);
    }
    
    hideLinkEditorDialog();
    
    if (currentEditor) {
        // Focus editor to ensure it's the active element
        currentEditor.focus();
        // Trigger input event to ensure sync happens
        currentEditor.trigger('input');
        syncToHiddenField(getWrapper(currentEditor));
    }
}

function createLinkElement(linkData) {
    // Use saved range if available, otherwise try to get current selection
    let range = savedRange;
    if (!range) {
        const selection = window.getSelection();
        if (!selection.rangeCount) return;
        range = selection.getRangeAt(0);
    }
    
    // Focus the editor first to ensure range is valid
    if (currentEditor) {
        currentEditor.focus();
    }
    
    // Restore the range
    const selection = window.getSelection();
    selection.removeAllRanges();
    selection.addRange(range);
    
    range.deleteContents();
    
    const link = document.createElement('a');
    link.textContent = linkData.text;
    link.setAttribute('data-link-target', linkData.target);
    link.setAttribute('data-link-type', linkData.type);
    link.setAttribute('contenteditable', 'false');
    
    if (linkData.type === 'url') {
        link.setAttribute('data-link-url', linkData.url);
    } else {
        // Page or article - store ID in data-link-id
        link.setAttribute('data-link-id', linkData.id);
    }
    
    range.insertNode(link);
    
    // Move cursor after the link
    range.setStartAfter(link);
    range.setEndAfter(link);
    selection.removeAllRanges();
    selection.addRange(range);
}

function updateLinkElement($linkElement, linkData) {
    $linkElement.text(linkData.text);
    $linkElement.attr('data-link-target', linkData.target);
    $linkElement.attr('data-link-type', linkData.type);
    $linkElement.attr('contenteditable', 'false');
    
    if (linkData.type === 'url') {
        $linkElement.attr('data-link-url', linkData.url);
        $linkElement.removeAttr('data-link-id');
        $linkElement.removeAttr('href'); // Remove old href if present
    } else {
        // Page or article - store ID in data-link-id
        $linkElement.removeAttr('data-link-url');
        $linkElement.removeAttr('href'); // Remove old href if present
        $linkElement.attr('data-link-id', linkData.id);
    }
}

function showLinkEditorDialog() {
    preserveEditorFocusState = true;

    if (currentEditor && currentEditor.length > 0) {
        currentEditor.closest('.rich-text-editor-wrapper').addClass('focused');
    }

    $dialog.fadeIn(200);
    $textInput.focus();
}

function hideLinkEditorDialog(restoreSelection) {
    var shouldRestoreSelection = !!restoreSelection && !currentLinkElement && !!savedRange && !!currentEditor;
    var editorToRestore = shouldRestoreSelection ? currentEditor : null;
    var rangeToRestore = shouldRestoreSelection ? savedRange.cloneRange() : null;

    $dialog.fadeOut(200, function() {
        if (editorToRestore && rangeToRestore) {
            editorToRestore.focus();
            editorToRestore.closest('.rich-text-editor-wrapper').addClass('focused');

            var selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(rangeToRestore);
        }

        preserveEditorFocusState = false;
    });
    
    // Clear all form fields
    $textInput.val('');
    $urlInput.val();
    $newTabCheckbox.prop('checked', true);
    
    // Clear all selections
    clearUrl();
    clearPage();
    clearArticle();
    
    currentLinkElement = null;
    currentEditor = null;
    savedRange = null;
}


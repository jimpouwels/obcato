// Rich Text Editor functionality
$(document).ready(function() {
    initRichTextEditors();
    initLinkEditorDialog();
    initFormSubmitHandler();
});

var currentLinkElement = null;
var currentEditor = null;

function initFormSubmitHandler() {
    // Sync all rich text editors before form submission
    $('form').on('submit', function() {
        console.log('Form submit - syncing all rich text editors');
        $('.rich-text-editor-wrapper').each(function() {
            syncToHiddenField($(this));
        });
    });
}

function initRichTextEditors() {
    // Ensure contenteditable is enabled
    $('.rich-text-content').each(function() {
        $(this).attr('contenteditable', 'true');
        $(this).prop('contentEditable', 'true');
        
        // Debug: log if contenteditable is working
        console.log('Rich text editor initialized:', $(this).attr('id'), 'Editable:', this.isContentEditable);
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
    
    // Handle double-click on links to edit them
    $('.rich-text-content').on('dblclick', 'a', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const editor = $(this).closest('.rich-text-content');
        editLink($(this), editor);
        return false;
    });
    
    // Prevent links from navigating when clicked in editor
    $('.rich-text-content').on('click', 'a', function(e) {
        e.preventDefault();
        return false;
    });
    
    // Update hidden textarea on content change
    $('.rich-text-content').on('input', function() {
        const wrapper = $(this).closest('.rich-text-editor-wrapper');
        syncToHiddenField(wrapper);
    });
    
    // Handle keyboard shortcuts and prevent typing in links
    $('.rich-text-content').on('keydown', function(e) {
        // Handle Enter key - insert <br> instead of <div>
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            
            const selection = window.getSelection();
            if (selection.rangeCount > 0) {
                const range = selection.getRangeAt(0);
                
                // Insert a <br> element
                const br = document.createElement('br');
                range.deleteContents();
                range.insertNode(br);
                
                // Move cursor after the br
                range.setStartAfter(br);
                range.setEndAfter(br);
                range.collapse(false);
                
                selection.removeAllRanges();
                selection.addRange(range);
                
                // Trigger input event for sync
                $(this).trigger('input');
            }
            return false;
        }
        // Ctrl+B for bold
        if (e.ctrlKey && e.key === 'b') {
            e.preventDefault();
            document.execCommand('bold', false, null);
            updateActiveButtons($(this));
            syncToHiddenField($(this).closest('.rich-text-editor-wrapper'));
            return false;
        }
        // Ctrl+I for italic
        else if (e.ctrlKey && e.key === 'i') {
            e.preventDefault();
            document.execCommand('italic', false, null);
            updateActiveButtons($(this));
            syncToHiddenField($(this).closest('.rich-text-editor-wrapper'));
            return false;
        }
        // Ctrl+U for underline
        else if (e.ctrlKey && e.key === 'u') {
            e.preventDefault();
            document.execCommand('underline', false, null);
            updateActiveButtons($(this));
            syncToHiddenField($(this).closest('.rich-text-editor-wrapper'));
            return false;
        }
        // Prevent typing inside links (except for special keys)
        else if (!e.ctrlKey && !e.metaKey && e.key.length === 1) {
            const selection = window.getSelection();
            if (selection.rangeCount > 0) {
                const range = selection.getRangeAt(0);
                let node = range.startContainer;
                
                // Check if we're inside a link element
                while (node && node !== this) {
                    if (node.nodeType === Node.ELEMENT_NODE && node.tagName === 'A') {
                        // We're inside a link - move cursor outside
                        e.preventDefault();
                        
                        // Create text node with the typed character
                        const textNode = document.createTextNode(e.key);
                        
                        // Insert after the link
                        if (node.nextSibling) {
                            node.parentNode.insertBefore(textNode, node.nextSibling);
                        } else {
                            node.parentNode.appendChild(textNode);
                        }
                        
                        // Move cursor after the new text
                        range.setStartAfter(textNode);
                        range.setEndAfter(textNode);
                        selection.removeAllRanges();
                        selection.addRange(range);
                        
                        // Trigger input event for sync
                        $(this).trigger('input');
                        return false;
                    }
                    node = node.parentNode;
                }
            }
        }
    });
    
    // Update active button states on selection change
    $('.rich-text-content').on('mouseup keyup', function() {
        updateActiveButtons($(this));
    });
    
    // Initialize content and height
    $('.rich-text-content').each(function() {
        adjustHeight(this);
    }).on('input', function() {
        adjustHeight(this);
    });
}

function syncToHiddenField(wrapper) {
    const editor = wrapper.find('.rich-text-content');
    const hiddenTextarea = wrapper.find('textarea');
    const htmlContent = editor.html();
    
    console.log('syncToHiddenField called');
    console.log('Editor found:', editor.length > 0);
    console.log('Textarea found:', hiddenTextarea.length > 0);
    console.log('Textarea name:', hiddenTextarea.attr('name'));
    console.log('Content length:', htmlContent.length);
    
    hiddenTextarea.val(htmlContent);
    console.log('Textarea value after sync:', hiddenTextarea.val().length);
}

function adjustHeight(element) {
    // Reset height to get correct scrollHeight
    const currentHeight = element.style.height;
    element.style.height = 'auto';
    const newHeight = Math.max(element.scrollHeight, 120); // Minimum 120px
    element.style.height = newHeight + 'px';
}

function updateActiveButtons(editor) {
    const toolbar = editor.closest('.rich-text-editor-wrapper').find('.rich-text-toolbar');
    
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
    
    currentEditor = editor;
    currentLinkElement = null;
    
    // Reset form
    $('#link-editor-text').val(selectedText || '');
    $('#link-editor-url').val('https://');
    $('#link-new-tab').prop('checked', true);
    $('#link-editor-delete').hide();
    $('#link-editor-title').text('Link toevoegen');
    
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
    
    $('#link-editor-text').val(linkElement.text());
    $('#link-editor-delete').show();
    $('#link-editor-title').text('Link bewerken');
    
    const linkTarget = linkElement.attr('data-link-target') || 'external';
    $('#link-new-tab').prop('checked', linkTarget === 'external');
    
    // Determine link type and load appropriate data
    const linkType = linkElement.attr('data-link-type') || 'url';
    const linkId = linkElement.attr('data-link-id');
    const href = linkElement.attr('href');
    
    // Clear all selections first
    clearUrl();
    clearPage();
    clearArticle();
    
    // Switch to appropriate tab
    switchLinkTab(linkType);
    
    if (linkType === 'url' && href && href !== '#') {
        // Show URL as selected
        selectUrl(href);
    } else if (linkType === 'page' && linkId) {
        // Show page placeholder, then fetch real title
        $('#link-page-selected').show().find('.selected-item-name').text(linkElement.text());
        $('#link-page-selected').data('page-id', linkId);
        $('#link-page-search').val('').closest('.form-field-group').hide();
        
        // Fetch actual page title
        $.ajax({
            url: '/admin/api/page/get?id=' + linkId,
            method: 'GET',
            success: function(page) {
                if (page && page.title) {
                    $('#link-page-selected .selected-item-name').text(page.title);
                }
            }
        });
    } else if (linkType === 'article' && linkId) {
        // Show article placeholder, then fetch real title
        $('#link-article-selected').show().find('.selected-item-name').text(linkElement.text());
        $('#link-article-selected').data('article-id', linkId);
        $('#link-article-search').val('').closest('.form-field-group').hide();
        
        // Fetch actual article title
        $.ajax({
            url: '/admin/api/article/get?id=' + linkId,
            method: 'GET',
            success: function(article) {
                if (article && article.title) {
                    $('#link-article-selected .selected-item-name').text(article.title);
                }
            }
        });
    }
    
    showLinkEditorDialog();
}

var linkSearchTimeout = null;

function initLinkEditorDialog() {
    // Tab switching
    $('.link-type-tab').on('click', function() {
        const tab = $(this).data('tab');
        switchLinkTab(tab);
    });
    
    // Page search
    $('#link-page-search').on('input', function() {
        const keyword = $(this).val().trim();
        clearTimeout(linkSearchTimeout);
        
        if (keyword.length < 2) {
            $('#link-page-results').empty();
            return;
        }
        
        linkSearchTimeout = setTimeout(function() {
            searchPages(keyword);
        }, 300);
    });
    
    // Article search
    $('#link-article-search').on('input', function() {
        const keyword = $(this).val().trim();
        clearTimeout(linkSearchTimeout);
        
        if (keyword.length < 2) {
            $('#link-article-results').empty();
            return;
        }
        
        linkSearchTimeout = setTimeout(function() {
            searchArticles(keyword);
        }, 300);
    });
    
    // URL add button
    $('#link-url-add').on('click', function() {
        const url = $('#link-editor-url').val().trim();
        if (url && url !== 'https://') {
            selectUrl(url);
        }
    });
    
    // Clear URL selection
    $('#link-url-selected .clear-selection').on('click', function() {
        clearUrl();
    });
    
    // Clear page selection
    $('#link-page-selected .clear-selection').on('click', function() {
        clearPage();
    });
    
    // Clear article selection
    $('#link-article-selected .clear-selection').on('click', function() {
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
                const wrapper = currentEditor.closest('.rich-text-editor-wrapper');
                syncToHiddenField(wrapper);
            }
        }
    });
    
    // Cancel button
    $('#link-editor-cancel').on('click', function() {
        hideLinkEditorDialog();
    });
    
    // Close on backdrop click
    $('.link-editor-backdrop').on('click', function() {
        hideLinkEditorDialog();
    });
    
    // Close on ESC key
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $('#link-editor-dialog').is(':visible')) {
            hideLinkEditorDialog();
        }
    });
    
    // Handle Enter key
    $('#link-editor-text, #link-editor-url').on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            $('#link-editor-save').click();
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
    if (tab === 'page' && !$('#link-page-selected').is(':visible')) {
        $('#link-page-search').closest('.form-field-group').show();
    }
    if (tab === 'article' && !$('#link-article-selected').is(':visible')) {
        $('#link-article-search').closest('.form-field-group').show();
    }
}

function searchPages(keyword) {
    $.ajax({
        url: '/admin/api/page/search?keyword=' + encodeURIComponent(keyword),
        method: 'GET',
        success: function(pages) {
            displayPageResults(pages);
        }
    });
}

function searchArticles(keyword) {
    $.ajax({
        url: '/admin/api/article/search?keyword=' + encodeURIComponent(keyword),
        method: 'GET',
        success: function(articles) {
            displayArticleResults(articles);
        }
    });
}

function displayPageResults(pages) {
    const $results = $('#link-page-results');
    $results.empty();
    
    if (pages.length === 0) {
        $results.html('<div class="link-search-no-results">Geen pagina\'s gevonden</div>');
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
        $results.append($item);
    });
}

function displayArticleResults(articles) {
    const $results = $('#link-article-results');
    $results.empty();
    
    if (articles.length === 0) {
        $results.html('<div class="link-search-no-results">Geen artikelen gevonden</div>');
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
        $results.append($item);
    });
}

function selectUrl(url) {
    // Clear other selections (mutual exclusion)
    clearPage();
    clearArticle();
    
    // Normalize URL
    if (!url.match(/^https?:\/\//i)) {
        url = 'https://' + url;
    }
    
    // Show URL as selected
    $('#link-url-selected').show().find('.selected-item-name').text(url);
    $('#link-url-selected').data('url', url);
    $('#link-url-input-group').hide();
}

function selectPage(pageId, pageTitle) {
    // Clear other selections (mutual exclusion)
    clearUrl();
    clearArticle();
    
    // Show page selection
    $('#link-page-selected').show().find('.selected-item-name').text(pageTitle);
    $('#link-page-selected').data('page-id', pageId);
    $('#link-page-results').empty();
    $('#link-page-search').val('').closest('.form-field-group').hide();
}

function selectArticle(articleId, articleTitle) {
    // Clear other selections (mutual exclusion)
    clearUrl();
    clearPage();
    
    // Show article selection
    $('#link-article-selected').show().find('.selected-item-name').text(articleTitle);
    $('#link-article-selected').data('article-id', articleId);
    $('#link-article-results').empty();
    $('#link-article-search').val('').closest('.form-field-group').hide();
}

function clearUrl() {
    $('#link-url-selected').hide().data('url', null);
    $('#link-editor-url').val('https://');
    $('#link-url-input-group').show();
}

function clearPage() {
    $('#link-page-selected').hide().data('page-id', null);
    $('#link-page-search').val('').closest('.form-field-group').show();
    $('#link-page-results').empty();
}

function clearArticle() {
    $('#link-article-selected').hide().data('article-id', null);
    $('#link-article-search').val('').closest('.form-field-group').show();
    $('#link-article-results').empty();
}

function saveLinkFromDialog() {
    const text = $('#link-editor-text').val().trim();
    const newTab = $('#link-new-tab').is(':checked');
    const linkTarget = newTab ? 'external' : 'internal';
    
    if (!text) {
        return;
    }
    
    // Check which item is selected (only one can be selected at a time)
    const url = $('#link-url-selected').data('url');
    const pageId = $('#link-page-selected').data('page-id');
    const articleId = $('#link-article-selected').data('article-id');
    
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
        const wrapper = currentEditor.closest('.rich-text-editor-wrapper');
        syncToHiddenField(wrapper);
    }
}

function createLinkElement(linkData) {
    const selection = window.getSelection();
    if (!selection.rangeCount) return;
    
    const range = selection.getRangeAt(0);
    range.deleteContents();
    
    const link = document.createElement('a');
    link.textContent = linkData.text;
    link.setAttribute('data-link-target', linkData.target);
    link.setAttribute('data-link-type', linkData.type);
    link.setAttribute('contenteditable', 'false');
    
    if (linkData.type === 'url') {
        link.href = linkData.url;
    } else {
        // Page or article - store ID in data-link-id
        link.setAttribute('data-link-id', linkData.id);
    }
    
    range.insertNode(link);
    
    // Insert a space after the link so cursor is outside of link context
    const space = document.createTextNode('\u00A0'); // Non-breaking space
    link.parentNode.insertBefore(space, link.nextSibling);
    
    // Move cursor after the space
    range.setStartAfter(space);
    range.setEndAfter(space);
    selection.removeAllRanges();
    selection.addRange(range);
}

function updateLinkElement($linkElement, linkData) {
    $linkElement.text(linkData.text);
    $linkElement.attr('data-link-target', linkData.target);
    $linkElement.attr('data-link-type', linkData.type);
    
    if (linkData.type === 'url') {
        $linkElement.attr('href', linkData.url);
        $linkElement.removeAttr('data-link-id');
    } else {
        // Page or article - store ID in data-link-id
        $linkElement.removeAttr('href');
        $linkElement.attr('data-link-id', linkData.id);
    }
}

function showLinkEditorDialog() {
    $('#link-editor-dialog').fadeIn(200);
    $('#link-editor-text').focus();
}

function hideLinkEditorDialog() {
    $('#link-editor-dialog').fadeOut(200);
    
    // Clear all form fields
    $('#link-editor-text').val('');
    $('#link-editor-url').val('https://');
    $('#link-new-tab').prop('checked', true);
    
    // Clear all selections
    clearUrl();
    clearPage();
    clearArticle();
    
    currentLinkElement = null;
    currentEditor = null;
}


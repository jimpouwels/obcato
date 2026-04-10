<div class="article-lookup" id="article-lookup-{$field_name}">
    <input type="hidden" id="{$field_name}" name="{$field_name}" value="{$field_value}" />
    {if $delete_field_name}
        <input type="hidden" id="{$delete_field_name}" name="{$delete_field_name}" value="" />
    {/if}

    <div class="article-lookup-summary" id="article-lookup-summary-{$field_name}">
        <div class="article-lookup-selected" id="article-lookup-selected-{$field_name}" {if !$selected_article_id}style="display:none;"{/if}>
            <span class="article-lookup-selected-label">{$text_resources.article_editor_select_parent_article_label}:</span>
            <span class="article-lookup-selected-title" id="article-lookup-selected-title-{$field_name}">{$selected_article_title|escape}</span>
        </div>
        <button type="button" class="article-lookup-select-btn" id="article-lookup-select-btn-{$field_name}" onclick="openArticleLookup('{$field_name}'); return false;" {if $selected_article_id}style="display:none;"{/if}>{$text_resources.object_picker_button_title}</button>
        <button type="button" class="article-lookup-edit-btn" id="article-lookup-edit-btn-{$field_name}" onclick="openArticleLookup('{$field_name}'); return false;" title="{$text_resources.object_picker_button_title|escape}" {if !$selected_article_id}style="display:none;"{/if}>
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M4 20h4l10-10-4-4L4 16v4Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                <path d="m12 6 4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
        </button>
    </div>

    <div id="article-lookup-modal-{$field_name}" class="article-lookup-modal" style="display:none;"
         data-field-name="{$field_name}"
         data-delete-field="{$delete_field_name}"
         data-exclude-id="{$exclude_article_id}"
         data-search-endpoint="{$search_endpoint|escape}"
         data-start-typing="{$text_resources.image_selector_start_typing|escape}"
         data-searching="{$text_resources.image_selector_searching|escape}"
         data-no-results="{$text_resources.articles_list_message_no_articles_found|escape}"
         data-remove-selection="{$text_resources.article_editor_delete_parent_article_label|escape}">
        <div class="article-lookup-backdrop" onclick="closeArticleLookup('{$field_name}');"></div>
        <div class="article-lookup-content">
            <div class="article-lookup-header">
                <h3>{$text_resources.article_editor_parent_article_label}</h3>
                <button type="button" class="article-lookup-close" onclick="closeArticleLookup('{$field_name}');">&times;</button>
            </div>

            <div class="article-lookup-body">
                <div class="article-lookup-search-row">
                    <input type="text" id="article-lookup-search-{$field_name}" class="article-lookup-search-input" placeholder="{$text_resources.articles_search_box_query|escape}" />
                </div>

                <div class="article-lookup-current" id="article-lookup-current-{$field_name}" {if !$selected_article_id}style="display:none;"{/if}>
                    <div class="article-lookup-current-title" id="article-lookup-current-title-{$field_name}">{$selected_article_title|escape}</div>
                    <button type="button" class="article-lookup-remove-btn" onclick="removeArticleLookupSelection('{$field_name}');">{$text_resources.article_editor_delete_parent_article_label}</button>
                </div>

                <div id="article-lookup-results-{$field_name}" class="article-lookup-results">
                    <div class="article-lookup-placeholder">{$text_resources.image_selector_start_typing}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        if (!window.__articleLookupComponentInitialized) {
            window.__articleLookupComponentInitialized = true;
            window.__articleLookupSearchTimeouts = {};
            window.__activeArticleLookupField = null;

            window.articleLookupEscapeHtml = function(value) {
                return String(value)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#39;');
            };

            window.initArticleLookup = function(fieldName, selectedTitle) {
                var $modal = $('#article-lookup-modal-' + fieldName);
                if ($modal.length === 0) {
                    return;
                }

                var title = selectedTitle || '';
                $modal.data('selected-title', title);
            };

            window.openArticleLookup = function(fieldName) {
                var $modal = $('#article-lookup-modal-' + fieldName);
                var $searchInput = $('#article-lookup-search-' + fieldName);
                var $results = $('#article-lookup-results-' + fieldName);

                if ($modal.length === 0) {
                    return;
                }

                var startTypingText = $modal.data('start-typing') || '';
                window.__activeArticleLookupField = fieldName;

                $modal.fadeIn(200);
                $results.html('<div class="article-lookup-placeholder">' + window.articleLookupEscapeHtml(startTypingText) + '</div>');
                $searchInput.val('');

                setTimeout(function() {
                    $searchInput.focus();
                }, 250);

                $searchInput.off('input.articleLookup').on('input.articleLookup', function() {
                    var keyword = $(this).val().trim();
                    if (window.__articleLookupSearchTimeouts[fieldName]) {
                        clearTimeout(window.__articleLookupSearchTimeouts[fieldName]);
                    }

                    if (keyword.length < 2) {
                        $results.html('<div class="article-lookup-placeholder">' + window.articleLookupEscapeHtml(startTypingText) + '</div>');
                        return;
                    }

                    window.__articleLookupSearchTimeouts[fieldName] = setTimeout(function() {
                        window.searchArticleLookup(fieldName, keyword);
                    }, 300);
                });

                $(document).off('keydown.articleLookup').on('keydown.articleLookup', function(e) {
                    if (e.key === 'Escape' || e.keyCode === 27) {
                        if (window.__activeArticleLookupField) {
                            window.closeArticleLookup(window.__activeArticleLookupField);
                        }
                    }
                });
            };

            window.closeArticleLookup = function(fieldName) {
                var $modal = $('#article-lookup-modal-' + fieldName);
                var $searchInput = $('#article-lookup-search-' + fieldName);
                var $results = $('#article-lookup-results-' + fieldName);

                if ($modal.length === 0) {
                    return;
                }

                var startTypingText = $modal.data('start-typing') || '';

                if (window.__articleLookupSearchTimeouts[fieldName]) {
                    clearTimeout(window.__articleLookupSearchTimeouts[fieldName]);
                    delete window.__articleLookupSearchTimeouts[fieldName];
                }
                $modal.fadeOut(200);
                $searchInput.val('');
                $results.html('<div class="article-lookup-placeholder">' + window.articleLookupEscapeHtml(startTypingText) + '</div>');

                if (window.__activeArticleLookupField === fieldName) {
                    window.__activeArticleLookupField = null;
                }

                $(document).off('keydown.articleLookup');
            };

            window.searchArticleLookup = function(fieldName, keyword) {
                var $modal = $('#article-lookup-modal-' + fieldName);
                var $results = $('#article-lookup-results-' + fieldName);

                if ($modal.length === 0) {
                    return;
                }

                var excludeId = parseInt($modal.data('exclude-id'), 10) || 0;
                var searchEndpoint = $modal.data('search-endpoint') || '/admin/api/article/search';
                var searchingText = $modal.data('searching') || '';
                var noResultsText = $modal.data('no-results') || '';

                $results.html('<div class="article-lookup-placeholder">' + window.articleLookupEscapeHtml(searchingText) + '</div>');

                $.ajax({
                    url: searchEndpoint + '?keyword=' + encodeURIComponent(keyword),
                    method: 'GET',
                    success: function(articles) {
                        var filteredArticles = [];

                        if (articles && articles.length) {
                            for (var i = 0; i < articles.length; i++) {
                                if (parseInt(articles[i].id, 10) !== excludeId) {
                                    filteredArticles.push(articles[i]);
                                }
                            }
                        }

                        if (!filteredArticles.length) {
                            $results.html('<div class="article-lookup-placeholder">' + window.articleLookupEscapeHtml(noResultsText) + '</div>');
                            return;
                        }

                        $results.empty();
                        for (var j = 0; j < filteredArticles.length; j++) {
                            var article = filteredArticles[j];
                            var title = article.title || '';
                            var intro = article.intro || '';
                            var $item = $('<button type="button" class="article-lookup-result-item"></button>');

                            $item.append('<span class="article-lookup-result-title">' + window.articleLookupEscapeHtml(title) + '</span>');
                            if (intro) {
                                $item.append('<span class="article-lookup-result-intro">' + window.articleLookupEscapeHtml(intro) + '</span>');
                            }

                            $item.on('click', (function(id, selectedTitle) {
                                return function() {
                                    window.selectArticleLookup(fieldName, id, selectedTitle);
                                };
                            })(article.id, title));

                            $results.append($item);
                        }
                    },
                    error: function() {
                        $results.html('<div class="article-lookup-placeholder">' + window.articleLookupEscapeHtml(noResultsText) + '</div>');
                    }
                });
            };

            window.selectArticleLookup = function(fieldName, articleId, articleTitle) {
                var $field = $('#' + fieldName);
                var $modal = $('#article-lookup-modal-' + fieldName);
                var $deleteField = $('#' + ($modal.data('delete-field') || ''));

                $field.val(articleId);
                if ($deleteField.length > 0) {
                    $deleteField.val('');
                }

                $('#article-lookup-selected-title-' + fieldName).text(articleTitle || '');
                $('#article-lookup-current-title-' + fieldName).text(articleTitle || '');
                $('#article-lookup-selected-' + fieldName).show();
                $('#article-lookup-current-' + fieldName).show();
                $('#article-lookup-select-btn-' + fieldName).hide();
                $('#article-lookup-edit-btn-' + fieldName).show();

                window.closeArticleLookup(fieldName);
            };

            window.removeArticleLookupSelection = function(fieldName) {
                var $field = $('#' + fieldName);
                var $modal = $('#article-lookup-modal-' + fieldName);
                var $deleteField = $('#' + ($modal.data('delete-field') || ''));

                $field.val('');
                if ($deleteField.length > 0) {
                    $deleteField.val('true');
                }

                $('#article-lookup-selected-title-' + fieldName).text('');
                $('#article-lookup-current-title-' + fieldName).text('');
                $('#article-lookup-selected-' + fieldName).hide();
                $('#article-lookup-current-' + fieldName).hide();
                $('#article-lookup-edit-btn-' + fieldName).hide();
                $('#article-lookup-select-btn-' + fieldName).show();

                window.closeArticleLookup(fieldName);
            };
        }

        if (typeof window.initArticleLookup === 'function') {
            window.initArticleLookup('{$field_name}', '{$selected_article_title|escape:"javascript"}');
        }
    });
</script>

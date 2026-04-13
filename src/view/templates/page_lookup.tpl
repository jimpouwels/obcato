<div class="article-lookup" id="page-lookup-{$field_name}">
    <input type="hidden" id="{$field_name}" name="{$field_name}" value="{$field_value}" />
    {if $delete_field_name}
        <input type="hidden" id="{$delete_field_name}" name="{$delete_field_name}" value="" />
    {/if}

    <div class="article-lookup-summary" id="page-lookup-summary-{$field_name}">
        <div class="article-lookup-selected" id="page-lookup-selected-{$field_name}" {if !$selected_page_id}style="display:none;"{/if}>
            <span class="article-lookup-selected-label">{$lookup_selected_label}:</span>
            <span class="article-lookup-selected-title" id="page-lookup-selected-title-{$field_name}">{$selected_page_title|escape}</span>
        </div>
        <button type="button" class="article-lookup-select-btn" id="page-lookup-select-btn-{$field_name}" onclick="openPageLookup('{$field_name}'); return false;" {if $selected_page_id}style="display:none;"{/if}>{$lookup_edit_button}</button>
        <button type="button" class="article-lookup-edit-btn" id="page-lookup-edit-btn-{$field_name}" onclick="openPageLookup('{$field_name}'); return false;" title="{$lookup_edit_button|escape}" {if !$selected_page_id}style="display:none;"{/if}>
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M4 20h4l10-10-4-4L4 16v4Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                <path d="m12 6 4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
        </button>
    </div>

    <div id="page-lookup-modal-{$field_name}" class="article-lookup-modal" style="display:none;"
         data-field-name="{$field_name}"
         data-delete-field="{$delete_field_name}"
            data-submit-click-id="{$submit_click_id}"
         data-exclude-id="{$exclude_page_id}"
         data-search-endpoint="{$search_endpoint|escape}"
         data-start-typing="{$lookup_start_typing|escape}"
         data-searching="{$lookup_searching|escape}"
         data-no-results="{$lookup_no_results|escape}"
         data-remove-selection="{$lookup_remove|escape}">
        <div class="article-lookup-backdrop" onclick="closePageLookup('{$field_name}');"></div>
        <div class="article-lookup-content">
            <div class="article-lookup-header">
                <h3>{$lookup_modal_title}</h3>
                <button type="button" class="article-lookup-close" onclick="closePageLookup('{$field_name}');">&times;</button>
            </div>

            <div class="article-lookup-body">
                <div class="article-lookup-search-row">
                    <input type="text" id="page-lookup-search-{$field_name}" class="article-lookup-search-input" placeholder="{$lookup_search_placeholder|escape}" />
                </div>

                <div class="article-lookup-current" id="page-lookup-current-{$field_name}" {if !$selected_page_id || !$allow_remove}style="display:none;"{/if}>
                    <div class="article-lookup-current-title" id="page-lookup-current-title-{$field_name}">{$selected_page_title|escape}</div>
                    <button type="button" class="article-lookup-remove-btn" onclick="removePageLookupSelection('{$field_name}');">{$lookup_remove}</button>
                </div>

                <div id="page-lookup-results-{$field_name}" class="article-lookup-results">
                    <div class="article-lookup-placeholder">{$lookup_start_typing}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        if (!window.__pageLookupComponentInitialized) {
            window.__pageLookupComponentInitialized = true;
            window.__pageLookupSearchTimeouts = {};
            window.__activePageLookupField = null;

            window.pageLookupEscapeHtml = function(value) {
                return String(value)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/\"/g, '&quot;')
                    .replace(/'/g, '&#39;');
            };

            window.initPageLookup = function(fieldName) {
                var $modal = $('#page-lookup-modal-' + fieldName);
                if ($modal.length === 0) {
                    return;
                }
            };

            window.openPageLookup = function(fieldName) {
                var $modal = $('#page-lookup-modal-' + fieldName);
                var $searchInput = $('#page-lookup-search-' + fieldName);
                var $results = $('#page-lookup-results-' + fieldName);

                if ($modal.length === 0) {
                    return;
                }

                var startTypingText = $modal.data('start-typing') || '';
                window.__activePageLookupField = fieldName;

                $modal.fadeIn(200);
                $results.html('<div class="article-lookup-placeholder">' + window.pageLookupEscapeHtml(startTypingText) + '</div>');
                $searchInput.val('');

                setTimeout(function() {
                    $searchInput.focus();
                }, 250);

                $searchInput.off('input.pageLookup').on('input.pageLookup', function() {
                    var keyword = $(this).val().trim();
                    if (window.__pageLookupSearchTimeouts[fieldName]) {
                        clearTimeout(window.__pageLookupSearchTimeouts[fieldName]);
                    }

                    if (keyword.length < 2) {
                        $results.html('<div class="article-lookup-placeholder">' + window.pageLookupEscapeHtml(startTypingText) + '</div>');
                        return;
                    }

                    window.__pageLookupSearchTimeouts[fieldName] = setTimeout(function() {
                        window.searchPageLookup(fieldName, keyword);
                    }, 300);
                });

                $(document).off('keydown.pageLookup').on('keydown.pageLookup', function(e) {
                    if (e.key === 'Escape' || e.keyCode === 27) {
                        if (window.__activePageLookupField) {
                            window.closePageLookup(window.__activePageLookupField);
                        }
                    }
                });
            };

            window.closePageLookup = function(fieldName) {
                var $modal = $('#page-lookup-modal-' + fieldName);
                var $searchInput = $('#page-lookup-search-' + fieldName);
                var $results = $('#page-lookup-results-' + fieldName);

                if ($modal.length === 0) {
                    return;
                }

                var startTypingText = $modal.data('start-typing') || '';

                if (window.__pageLookupSearchTimeouts[fieldName]) {
                    clearTimeout(window.__pageLookupSearchTimeouts[fieldName]);
                    delete window.__pageLookupSearchTimeouts[fieldName];
                }
                $modal.fadeOut(200);
                $searchInput.val('');
                $results.html('<div class="article-lookup-placeholder">' + window.pageLookupEscapeHtml(startTypingText) + '</div>');

                if (window.__activePageLookupField === fieldName) {
                    window.__activePageLookupField = null;
                }

                $(document).off('keydown.pageLookup');
            };

            window.searchPageLookup = function(fieldName, keyword) {
                var $modal = $('#page-lookup-modal-' + fieldName);
                var $results = $('#page-lookup-results-' + fieldName);

                if ($modal.length === 0) {
                    return;
                }

                var excludeId = parseInt($modal.data('exclude-id'), 10) || 0;
                var searchEndpoint = $modal.data('search-endpoint') || '/admin/api/page/search';
                var noResultsText = $modal.data('no-results') || '';

                $results.html('<div class="ajax-spinner"><span></span></div>');

                $.ajax({
                    url: searchEndpoint + '?keyword=' + encodeURIComponent(keyword),
                    method: 'GET',
                    success: function(pages) {
                        var filteredPages = [];

                        if (pages && pages.length) {
                            for (var i = 0; i < pages.length; i++) {
                                if (parseInt(pages[i].id, 10) !== excludeId) {
                                    filteredPages.push(pages[i]);
                                }
                            }
                        }

                        if (!filteredPages.length) {
                            $results.html('<div class="article-lookup-placeholder">' + window.pageLookupEscapeHtml(noResultsText) + '</div>');
                            return;
                        }

                        $results.empty();
                        for (var j = 0; j < filteredPages.length; j++) {
                            var page = filteredPages[j];
                            var title = page.title || '';
                            var path = page.path || '';
                            var $item = $('<button type="button" class="article-lookup-result-item"></button>');

                            $item.append('<span class="article-lookup-result-title">' + window.pageLookupEscapeHtml(title) + '</span>');
                            if (path) {
                                $item.append('<span class="article-lookup-result-intro">' + window.pageLookupEscapeHtml(path) + '</span>');
                            }

                            $item.on('click', (function(id, selectedTitle) {
                                return function() {
                                    window.selectPageLookup(fieldName, id, selectedTitle);
                                };
                            })(page.id, title));

                            $results.append($item);
                        }
                    },
                    error: function() {
                        $results.html('<div class="article-lookup-placeholder">' + window.pageLookupEscapeHtml(noResultsText) + '</div>');
                    }
                });
            };

            window.selectPageLookup = function(fieldName, pageId, pageTitle) {
                var $field = $('#' + fieldName);
                var $modal = $('#page-lookup-modal-' + fieldName);
                var $deleteField = $('#' + ($modal.data('delete-field') || ''));
                var submitClickId = $modal.data('submit-click-id');

                $field.val(pageId);
                if ($deleteField.length > 0) {
                    $deleteField.val('');
                }

                $('#page-lookup-selected-title-' + fieldName).text(pageTitle || '');
                $('#page-lookup-current-title-' + fieldName).text(pageTitle || '');
                $('#page-lookup-selected-' + fieldName).show();
                $('#page-lookup-current-' + fieldName).show();
                $('#page-lookup-select-btn-' + fieldName).hide();
                $('#page-lookup-edit-btn-' + fieldName).show();

                window.closePageLookup(fieldName);

                if (submitClickId) {
                    setTimeout(function() {
                        $('#' + submitClickId).click();
                    }, 10);
                }
            };

            window.removePageLookupSelection = function(fieldName) {
                var $field = $('#' + fieldName);
                var $modal = $('#page-lookup-modal-' + fieldName);
                var $deleteField = $('#' + ($modal.data('delete-field') || ''));
                var submitClickId = $modal.data('submit-click-id');

                $field.val('');
                if ($deleteField.length > 0) {
                    $deleteField.val('true');
                }

                $('#page-lookup-selected-title-' + fieldName).text('');
                $('#page-lookup-current-title-' + fieldName).text('');
                $('#page-lookup-selected-' + fieldName).hide();
                $('#page-lookup-current-' + fieldName).hide();
                $('#page-lookup-edit-btn-' + fieldName).hide();
                $('#page-lookup-select-btn-' + fieldName).show();

                window.closePageLookup(fieldName);

                if (submitClickId) {
                    setTimeout(function() {
                        $('#' + submitClickId).click();
                    }, 10);
                }
            };
        }

        if (typeof window.initPageLookup === 'function') {
            window.initPageLookup('{$field_name}');
        }
    });
</script>

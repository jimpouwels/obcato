{* Image selector modal *}
<div id="image-selector-modal-{$context_id}" class="image-selector-modal" style="display: none;">
    <div class="image-selector-backdrop" onclick="closeImageSelector('{$context_id}');"></div>
    <div class="image-selector-content">
        <div class="image-selector-header">
            <h3>{$text_resources.image_selector_title}</h3>
            <button type="button" class="close-btn" onclick="closeImageSelector('{$context_id}');">&times;</button>
        </div>
        <div class="image-selector-search">
            <input type="text" id="image-search-{$context_id}" placeholder="{$text_resources.image_selector_search_placeholder}" class="image-search-input" />
        </div>
        <div id="image-selector-grid-{$context_id}" class="image-selector-grid" data-start-typing="{$text_resources.image_selector_start_typing}" data-searching="{$text_resources.image_selector_searching}" data-no-results="{$text_resources.image_selector_no_results}">
            <div class="image-selector-loading">{$text_resources.image_selector_start_typing}</div>
        </div>
    </div>
</div>

{if $multiple_images}
<div id="photo_album_element_{$context_id}_selected_images" 
    class="selected-images-container"
    data-context-id="{$context_id}"
    data-entity-id="{$entity_id}"
    data-get-endpoint="{$get_endpoint}"
    data-update-endpoint="{$update_endpoint}"
    data-delete-endpoint="{$delete_endpoint}">
</div>
{else}
<input type="hidden" 
    id="{$field_name}" 
    name="{$field_name}" 
    value="{$field_value}" 
    data-context-id="{$context_id}"
    data-entity-id="{$entity_id}"
    data-get-endpoint="{$get_endpoint}"
    data-update-endpoint="{$update_endpoint}"
    data-delete-endpoint="{$delete_endpoint}" />
<div id="{$field_name}_preview" class="single-image-preview"></div>
{/if}

<div class="image-lookup-trigger">
    <button type="button" class="select-image-btn" onclick="openImageSelector('{$context_id}', {if $multiple_images}true{else}false{/if}); return false;">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
            <path d="M2 12.5L5.5 9L8 11.5L11.5 8L14 10.5M2 2H14V14H2V2Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span>{if $multiple_images}{$text_resources.image_selector_select_multiple}{else}{$text_resources.image_selector_select_single}{/if}</span>
    </button>
</div>

<script type="text/javascript">
    $(document).ready(() => {
        {if $multiple_images}
        updateSelectedImages({$context_id});
        {else}
        // Store field name for this context
        window['imageSelector_fieldName_{$context_id}'] = '{$field_name}';
        var currentImageId = $('#{$field_name}').val();
        if (currentImageId) {
            updateSingleImagePreview('{$field_name}', currentImageId);
        }
        {/if}
    });
</script>
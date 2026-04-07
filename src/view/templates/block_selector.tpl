<div class="block-selector-panel">
    <div class="block-selector-column">
        <label class="block-selector-label">{$text_resources.available_blocks}</label>
        <select class="block-selector-select" multiple="multiple" size="10" name="select_blocks_{$context_id}[]">
            {if count($blocks_to_select) > 0}
                {foreach from=$blocks_to_select item=block}
                    <option value="{$block.id}">{$block.title}</option>
                {/foreach}
            {else}
                <option value="-1"></option>
            {/if}
        </select>
    </div>

    <div class="selected-blocks-column">
        <label class="block-selector-label">{$text_resources.selected_blocks_header}</label>
        <div class="selected-blocks-list">
            {if count($selected_blocks) > 0}
                {foreach from=$selected_blocks item=selected_block}
                    <div class="selected-block-item{if !$selected_block.published} unpublished{/if}">
                        <div class="block-info">
                            <span class="block-title">{$selected_block.title}</span>
                            <span class="block-position">{$selected_block.position_name}</span>
                        </div>
                        <div class="block-delete">{$selected_block.delete_field}</div>
                    </div>
                {/foreach}
            {else}
                <div class="no-blocks-message">{$text_resources.no_selected_blocks_found_message}</div>
            {/if}
        </div>
    </div>
</div>
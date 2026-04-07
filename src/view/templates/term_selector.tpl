<div class="selector_panel modern-selector">
    <div class="selector-column">
        <label class="selector-label">{$label_selected_terms}</label>
        <select class="term-selector modern-select" multiple="multiple" size="10" name="select_terms_{$context_id}[]">
            {if count($terms_to_select) > 0}
                {foreach from=$terms_to_select item=term}
                    <option value="{$term.id}">{$term.name}</option>
                {/foreach}
            {else}
                <option value="-1"></option>
            {/if}
        </select>
    </div>

    <div class="selected-items-column">
        <label class="selector-label">Geselecteerd</label>
        <div class="selected-items-list">
            {if count($selected_terms) > 0}
                {foreach from=$selected_terms item=selected_term}
                    <div class="selected-item">
                        <span class="item-name">{$selected_term.name}</span>
                        <div class="item-delete">{$selected_term.delete_field}</div>
                    </div>
                {/foreach}
            {else}
                <div class="no-items-message">{$message_no_selected_terms}</div>
            {/if}
        </div>
    </div>
</div>

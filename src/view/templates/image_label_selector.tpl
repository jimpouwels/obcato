<div class="selector_panel modern-selector">
    {if isset($new_image_label_field)}
        {$new_image_label_field}
    {/if}

    <div class="selector-column">
        <label class="selector-label">Beschikbare labels</label>
        <select class="label-selector modern-select" multiple="multiple" size="10" name="select_labels_{$context_id}[]">
            {if count($all_labels) > 0}
                {foreach from=$all_labels item=label}
                    {if !$label.is_selected}
                        <option value="{$label.id}">{$label.name}</option>
                    {/if}
                {/foreach}
            {/if}
            {if (count($all_labels) == 0 && (count($all_labels) - count($image_labels) ==0))}
                <option value="-1"></option>
            {/if}
        </select>
    </div>

    <div class="selected-items-column">
        <label class="selector-label">Geselecteerd</label>
        <div class="selected-items-list">
            {if count($image_labels) > 0}
                {foreach from=$image_labels item=image_label}
                    <div class="selected-item">
                        <span class="item-name">{$image_label.name}</span>
                        <div class="item-delete">{$image_label.delete_checkbox}</div>
                    </div>
                {/foreach}
            {else}
                <div class="no-items-message">Geen labels geselecteerd</div>
            {/if}
        </div>
    </div>
</div>

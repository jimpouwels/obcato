<div class="admin_form_v2">
    <input type="hidden" value="" name="element{$id}_add_item" id="element{$id}_add_item" />
    <input type="hidden" value="{$list_item_order}" name="element_{$id}_list_item_order" id="element_{$id}_list_item_order" />
    {$title_field}
    <div class="panel-title">Items</div>
    <div id="list_element_{$id}_items" class="list-element-sortable-items" data-order-field-id="element_{$id}_list_item_order">
        {if count($list_items) > 0}
            {foreach from=$list_items item=list_item}
                <div class="list_element_values list-element-sortable-item" data-list-item-id="{$list_item.id}">
                    <div class="list_element_item_drag_handle" title="Reorder" aria-label="Reorder">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                    <div class="list_element_item_value_field">{$list_item.item_text_field}</div>
                    <div class="list_element_item_value_delete_field">{$list_item.delete_field}</div>
                </div>
            {/foreach}
        {/if}
    </div>
    {if count($list_items) == 0}
        <em class="list_element_empty_state">{$text_resources.list_element_message_no_list_items}</em>
    {/if}
    {$add_item_button}
</div>

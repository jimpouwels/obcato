<div class="admin_form_v2">
    <input type="hidden" value="" name="element{$id}_add_item" id="element{$id}_add_item"/>
    {$title_field}
    <div class="panel-title">Items</div>
    {if count($list_items) > 0}
        {foreach from=$list_items item=list_item}
            <div class="list_element_values">
                <div class="list_element_item_value_field">{$list_item.item_text_field}</div>
                <div class="list_element_item_value_delete_field">{$list_item.delete_field}</div>
            </div>
        {/foreach}
    {else}
        <em>{$text_resources.list_element_message_no_list_items}</em>
    {/if}
    {$add_item_button}
</div>

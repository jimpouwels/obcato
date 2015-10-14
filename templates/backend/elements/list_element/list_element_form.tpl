<table>
    <tr>
            <td>
                    <input type="hidden" value="" name="element{$id}_add_item" id="element{$id}_add_item" />
                    {$title_field}
            </td>
    </tr>
    <tr>
            <td>
                    <fieldset class="admin_fieldset list-fieldset">
                            <div class="fieldset-title">Items</div>
                            {if count($list_items) > 0}
                                    <table cellspacing="0" cellpadding="0" class="list_element_items">
                                            <tr>
                                                    <td><em>Waarde</em></td>
                                                    <td><em>Verwijder</em></td>
                                            </tr>
                                            {foreach from=$list_items item=list_item}
                                                    <tr>
                                                            <td>{$list_item.item_text_field}</td>
                                                            <td>{$list_item.delete_field}</td>
                                                    </tr>
                                            {/foreach}
                                    </table>
                            {else}
                                    <em>Dit element bevat nog geen items</em>
                            {/if}
                    </fieldset>
            </td>
    </tr>
    <tr>
            <td>{$add_item_button}</td>
    </tr>
</table>
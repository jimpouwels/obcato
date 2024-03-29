<div class="selector_panel">
    <select class="term-selector" multiple="multiple" size="10" name="select_terms_{$context_id}[]">
        {if count($terms_to_select) > 0}
            {foreach from=$terms_to_select item=term}
                <option value="{$term.id}">{$term.name}</option>
            {/foreach}
        {else}
            <option value="-1"></option>
        {/if}
    </select>

    <table class="selected-terms" cellpadding="0" cellspacing="0">
        <colgroup width="200px"></colgroup>
        <colgroup width="50px"></colgroup>
        <thead>
        <tr>
            <th>{$label_selected_terms}</th>
            <th>{$label_delete_selected_term}</th>
        </tr>
        </thead>
        <tbody>
        {if count($selected_terms) > 0}
            {foreach from=$selected_terms item=selected_term}
                <tr>
                    <td>{$selected_term.name}</td>
                    <td class="delete_column">{$selected_term.delete_field}</td>
                </tr>
            {/foreach}
        {else}
            <tr>
                <td><em>{$message_no_selected_terms}</em></td>
            </tr>
        {/if}
        </tbody>
    </table>
</div>

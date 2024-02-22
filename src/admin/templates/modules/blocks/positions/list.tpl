<form id="positions_delete_form" action="{$backend_base_url}" method="post">
    <input type="hidden" name="position_delete_action" id="position_delete_action" value=""/>
    {if count($all_positions) > 0}
        <table class="listing" cellpadding="5" cellspacing="0" border="0">
            <colgroup width="300px"></colgroup>
            <colgroup width="300px"></colgroup>
            <colgroup width="75px"></colgroup>
            <thead>
            <tr class="header">
                <th>{$text_resources.blocks_positions_list_name_column}</th>
                <th>{$text_resources.blocks_positions_list_description_column}</th>
                <th class="delete_column">{$text_resources.blocks_positions_list_delete_column}</th>
            </tr>
            </thead>
            <tbody>
            {foreach from=$all_positions item=position}
                <tr>
                    <td><a href="{$backend_base_url}&position={$position.id}"
                           title="{$position.name}">{$position.name}</a></td>
                    <td>{$position.explanation}</td>
                    <td class="delete_column">{$position.delete_field}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    {else}
        {$no_positions_message}
    {/if}
</form>

{if count($search_results) > 0}
    <table class="listing" width="95%" cellspacing="0" cellpadding="5" border="0">
        <colgroup width="50"></colgroup>
        <colgroup width="200px"></colgroup>
        <colgroup width="50px"></colgroup>
        <colgroup width="50px"></colgroup>
        <colgroup width="10px"></colgroup>
        <thead>
        <tr>
            <th>Titel</th>
            <th>Aangemaakt op</th>
            <th>Aangemaakt door</th>
            <th>Gepubliceerd</th>
        </tr>
        </thead>
        <tbody>
        {foreach from=$search_results item=search_result}
            <tr>
                <td><a href="{$backend_base_url}&download={$search_result.id}" title="{$search_result.title}">{$search_result.title}</a></td>
                <td>{$search_result.created_at}</td>
                <td>{$search_result.created_by}</td>
                <td>
                    {if $search_result.published}
                        <img alt="Publiceren" src="/admin/static.php?file=/default/img/default_icons/green_flag.png" />
                    {else}
                        <img alt="Depubliceren" src="/admin/static.php?file=/default/img/default_icons/red_flag.png" />
                    {/if}
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
{else}
    {$no_results_message}
{/if}

<div>
    <form method="post" id="update_sitewide_pages_form" action="{$backend_base_url}">
        <input type="hidden" name="action" id="action" value="" />
        <input type="hidden" name="moveSitewidePage" id="moveSitewidePage" value="" />

        <a href="#" id="update_sitewide_pages" class="displaynone"></a>
        {if !is_null($sitewide_pages) && count($sitewide_pages) > 0}
            <table class="listing">
                <colgroup style="width: 225px"></colgroup>
                <colgroup style="width: 40px"></colgroup>
                <colgroup style="width: 20px"></colgroup>
                <thead>
                <tr class="header">
                    <th>Paginatitel</th>
                    <th>Omhoog</th>
                    <th>Omlaag</th>
                    <th class="center_column">Verwijder</th>
                </tr>
                </thead>
                <tbody>
                {foreach from=$sitewide_pages item=sitewide_page}
                    <tr>
                        <td>{$sitewide_page.title}</td>
                        <td class="sitewide_page_move_cell">
                            {if $sitewide_page@iteration != 1}
                                <a href="#" onclick="moveUp({$sitewide_page.id});"><img src="/admin/static.php?file=/default/img/default_icons/up.png" /></a>
                            {/if}
                        </td>
                        <td class="sitewide_page_move_cell sitewide_page_move_down_cell">
                            {if $sitewide_page@iteration < count($sitewide_pages)}
                                <a href="#" onclick="moveDown({$sitewide_page.id});"><img src="/admin/static.php?file=/default/img/default_icons/up.png" /></a>
                            {/if}
                        </td>
                        <td class="delete_column center_column">
                            <label for="sitewide_page_{$sitewide_page.id}_delete" class="admin_label"></label>
                            <input type="checkbox" id="sitewide_page_{$sitewide_page.id}_delete"
                                   name="sitewide_page_{$sitewide_page.id}_delete" class="admin_field_checkbox" />
                        </td>
                    </tr>
                {/foreach}
                </tbody>
            </table>
        {/if}
        {$page_picker}
    </form>
</div>
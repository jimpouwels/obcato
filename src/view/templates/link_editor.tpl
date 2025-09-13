<input type="hidden" id="delete_link_target" name="delete_link_target" value="" />

{if isset($links) > 0}
    <table class="link-table" cellpadding="5px" cellspacing="0" border="0">
        <colgroup width="25px"></colgroup>
        <colgroup width="230px"></colgroup>
        <colgroup width="230px"></colgroup>
        <colgroup width="75px"></colgroup>
        <colgroup width="125px"></colgroup>
        <colgroup width="20px"></colgroup>

        <thead>
        <tr>
            <th></th>
            <th>{$text_resources.link_title}</th>
            <th>{$text_resources.link_target}</th>
            <th>{$text_resources.link_code}</th>
            <th>{$text_resources.link_open_in}</th>
            <th></th>
            <th>{$text_resources.link_delete}</th>
        </tr>
        </thead>
        <tbody>
        {foreach from=$links item=link}
            <tr>
                <td class="link-addlink">
                    <a href="#" onclick="putLink('{$link.id}'); return false;" title="{$text_resources.place_link}">
                        <img src="/admin/index.php?file=/default/img/default_icons/place_link.png"
                             alt="{$text_resources.place_link}" />
                    </a>
                </td>
                <td class="link-title">
                    {$link.title_field}
                </td>
                <td class="link-target">
                    {if !is_null($link.target_field)}
                        {$link.target_field}
                    {else}
                        <em>{$link.target_title}</em>
                        <span class="link_delete_link"><a href="#" onclick="deleteLink('{$link.id}'); return false;"
                                                          title="Verwijder linkdoel"><img
                                        src="/admin/index.php?file=/default/img/default_icons/delete_small.png"
                                        alt="Verwijder linkdoel" /></a></span>
                    {/if}
                </td>
                <td class="link-code">
                    {$link.code_field}
                </td>
                <td class="link-target">
                    {$link.target_screen_field}
                </td>
                <td class="link-button">
                    {$link.element_holder_picker}
                </td>
                <td class="link-delete">
                    {$link.delete_field}
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
{else}
    {$message}
{/if}

<div id="element_index_{$index}">    <span class="element_id_holder displaynone">{$id}</span>    <table class="element_wrapper {$identifier}" cellpadding="5" cellspacing="0">        <thead>            <tr class="element_header">                <th><img src="{$icon_url}" alt="{$type}" />&nbsp;{$type}</th>                <th class="template_picker">                    {$template_picker}                </th>                <th class="delete_button">                    <a href="#" onclick="deleteElement('{$id}','{$delete_element_form_id}');" title="Verwijder element">                        <img src="/admin/static.php?file=/default/img/default_icons/delete_small.png" alt="Verwijderen" title="Verwijderen" />                    </a>                </th>            </tr>        </thead>        <tbody>            <tr>                <td>                    {$element_form}                </td>            </tr>        </tbody>    </table></div>
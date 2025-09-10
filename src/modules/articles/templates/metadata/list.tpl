<form method="post" id="metadata_field_delete_form" action="{$backend_base_url}">
    <input type="hidden" name="metadata_field_delete_action" id="metadata_field_delete_action" value="" />

    {if !is_null($metadata_fields) && count($metadata_fields) > 0}
        <table class="listing">
            <colgroup style="width: 225px"></colgroup>
            <colgroup style="width: 40px"></colgroup>
            <colgroup style="width: 20px"></colgroup>
            <thead>
            <tr class="header">
                <th>Naam</th>
                <th>Standaardwaarde</th>
                <th class="center_column">Verwijder</th>
            </tr>
            </thead>
            <tbody>
            {foreach from=$metadata_fields item=metadata_field}
                <tr>
                    <td><a href="{$backend_base_url}&metadata_field={$metadata_field.id}" title="{$metadata_field.name}">{$metadata_field.name}</a></td>
                    <td>{$metadata_field.default_value}</td>
                    <td class="delete_column center_column">
                        <label for="sitewide_page_{$metadata_field.id}_delete" class="admin_label"></label>
                        <input type="checkbox" id="metadata_field_{$metadata_field.id}_delete"
                               name="metadata_field_{$metadata_field.id}_delete" class="admin_field_checkbox" />
                    </td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    {/if}
</form>
{if isset($metadata_field_editor)}
    {$metadata_field_editor}
{/if}

{$metadata_field_list}

<form id="add_metadata_field_form_hidden" class="displaynone" method="post" action="{$backend_base_url}">
    <fieldset>
        <input id="add_metadata_field_action" name="add_metadata_field_action" type="hidden" value="add_metadata_field" />
    </fieldset>
</form>

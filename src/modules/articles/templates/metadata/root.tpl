<div class="content_left_column">
    {$metadata_field_list}
</div>
<div class="content_right_column">
    {if isset($metadata_field_editor)}
        {$metadata_field_editor}
    {/if}
</div>

<form id="add_metadata_field_form_hidden" class="displaynone" method="post" action="{$backend_base_url}">
    <fieldset>
        <input id="add_metadata_field_action" name="add_metadata_field_action" type="hidden" value="add_metadata_field" />
    </fieldset>
</form>

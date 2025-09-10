{if isset($label_editor)}
    {$label_editor}
{/if}
{$labels_list}

<form id="add_form_hidden" class="displaynone" method="post" action="{$backend_base_url}">
    <input id="add_label_action" name="add_label_action" type="hidden" value="" />
</form>
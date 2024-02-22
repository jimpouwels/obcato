{if isset($term_editor)}
    {$term_editor}
{/if}

{$term_list}

<form id="add_term_form_hidden" class="displaynone" method="post" action="{$backend_base_url}">
    <fieldset>
        <input id="add_term_action" name="add_term_action" type="hidden" value=""/>
    </fieldset>
</form>

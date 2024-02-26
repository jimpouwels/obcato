<form id="add_form_hidden" class="displaynone" method="post" action="{$backend_base_url}">
    <fieldset>
        <input id="add_article_action" name="add_article_action" type="hidden" value=""/>
    </fieldset>
</form>

<div class="content_left_column">
    {$search}
</div>
<div class="content_right_column">
    {if isset($list)}
        {$list}
    {/if}
    {if isset($editor)}
        {$editor}
    {/if}
</div>

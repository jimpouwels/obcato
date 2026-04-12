<div class="images-module-wrapper">
    {$search}
    {if isset($editor)}
        <div class="images-editor-wrapper">
            {$editor}
        </div>
    {/if}
</div>

<form id="add_form_hidden" class="displaynone" method="post" action="{$backend_base_url}">
    <input id="add_image_action" name="add_image_action" type="hidden" value="" />
</form>

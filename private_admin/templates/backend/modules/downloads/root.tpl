<div class="content_left_column">
    {$search_box}
</div>
<div class="content_right_column">
    {if isset($list)}
        {$list}
    {else}
        {$editor}
    {/if}
</div>

<form id="add_form_hidden" class="displaynone" method="post" action="/admin/index.php">
    <input id="add_download_action" name="add_download_action" type="hidden" value="" />
</form>

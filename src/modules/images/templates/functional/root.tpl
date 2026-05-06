<div class="fimg-module-root">
<div class="content_left_column">
    <div class="panel">
        <div class="panel-content">
            <div class="fimg-tree">
                {include file="images/templates/functional/folder_tree.tpl" folders=$root_folders images=$root_images depth=0 current_image_id=$current_image_id current_folder_id=$current_folder_id}
            </div>
        </div>
    </div>
</div>
<div class="content_right_column fimg-content-right-column">
    {if $editor_mode != 'none'}
        {$editor}
    {else}
        <div class="fimg-empty-state">Selecteer een afbeelding of map om te bewerken, of voeg er een toe.</div>
    {/if}
</div>

<form id="add_fimg_form" class="displaynone" method="post" action="{$backend_base_url}">
    <input name="action" type="hidden" value="add_functional_image" />
    <input id="add_fimg_folder_id" name="fimg_folder_id" type="hidden" value="" />
</form>

<form id="add_fimg_folder_form" class="displaynone" method="post" action="{$backend_base_url}">
    <input name="action" type="hidden" value="add_functional_image_folder" />
    <input id="add_fimg_parent_folder_id" name="fimg_parent_folder_id" type="hidden" value="" />
</form>

<form id="delete_fimg_folder_form" class="displaynone" method="post" action="{$backend_base_url}">
    <input name="action" type="hidden" value="delete_functional_image_folder" />
    <input id="delete_fimg_folder_id" name="fimg_folder_id" type="hidden" value="" />
</form>

<form id="move_fimg_form" class="displaynone" method="post" action="{$backend_base_url}">
    <input name="action" type="hidden" value="move_functional_image" />
    <input id="move_fimg_id" name="fimg_id" type="hidden" value="" />
    <input id="move_fimg_folder_id" name="fimg_folder_id" type="hidden" value="" />
</form>
</div>

<div class="links-module-root">
<div class="content_left_column">
    <div class="panel">
        <div class="panel-content">
            <div class="links-tree">
                {include file="links/templates/folder_tree.tpl" folders=$root_folders links=$root_links depth=0 current_link_id=$current_link_id current_folder_id=$current_folder_id}
            </div>
        </div>
    </div>
</div>
<div class="content_right_column links-content-right-column">
    {if $editor_mode != 'none'}
        {$editor}
    {else}
        <div class="links-empty-state">Selecteer een link of map om te bewerken, of voeg er een toe.</div>
    {/if}
</div>

<form id="add_link_form" class="displaynone" method="post" action="{$backend_base_url}">
    <input name="action" type="hidden" value="add_link" />
    <input id="add_link_folder_id" name="folder_id" type="hidden" value="" />
</form>

<form id="add_folder_form" class="displaynone" method="post" action="{$backend_base_url}">
    <input name="action" type="hidden" value="add_folder" />
    <input id="add_folder_parent_id" name="parent_folder_id" type="hidden" value="" />
</form>

<form id="delete_folder_form" class="displaynone" method="post" action="{$backend_base_url}">
    <input name="action" type="hidden" value="delete_folder" />
    <input id="delete_folder_id" name="folder_id" type="hidden" value="" />
    <input id="delete_folder_mode" name="delete_mode" type="hidden" value="" />
</form>

<form id="move_link_form" class="displaynone" method="post" action="{$backend_base_url}">
    <input name="action" type="hidden" value="move_link" />
    <input id="move_link_id" name="link_id" type="hidden" value="" />
    <input id="move_link_folder_id" name="folder_id" type="hidden" value="" />
</form>
</div>

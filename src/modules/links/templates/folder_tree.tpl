{* Recursive folder tree - always fully expanded *}
{foreach $folders as $folder}
<div class="links-folder" data-folder-id="{$folder.id}">
    <div class="links-folder-header">
        <span class="links-folder-icon">&#128193;</span>
        <a href="{$backend_base_url}&amp;folder={$folder.id}" class="links-folder-name{if $current_folder_id == $folder.id} selected{/if}">{$folder.name|escape}</a>
        <span class="links-folder-actions">
            <button type="button" class="links-folder-add-link" data-folder-id="{$folder.id}" title="Link toevoegen in deze map">+&#128279;</button>
            <button type="button" class="links-folder-add-subfolder" data-folder-id="{$folder.id}" title="Submap toevoegen">+&#128193;</button>
            <button type="button" class="links-folder-delete" data-folder-id="{$folder.id}" data-folder-name="{$folder.name|escape:'html'}" title="Map verwijderen">&#128465;</button>
        </span>
    </div>
    <div class="links-folder-children">
        {include file="links/templates/folder_tree.tpl" folders=$folder.sub_folders links=$folder.links depth=$depth+1 current_link_id=$current_link_id current_folder_id=$current_folder_id}
    </div>
</div>
{/foreach}

{foreach $links as $link}
<div class="links-link-item{if $current_link_id == $link.id} selected{/if}" data-link-id="{$link.id}" draggable="true">
    <span class="links-link-icon">&#128279;</span>
    <a href="{$backend_base_url}&amp;link={$link.id}" class="links-link-title">{$link.title|escape}</a>
    <span class="links-link-url">{$link.url|escape|truncate:60}</span>
</div>
{/foreach}

{if empty($folders) && empty($links) && $depth == 0}
    <div class="links-empty-state">Nog geen links. Gebruik de knoppen hierboven om links of mappen toe te voegen.</div>
{/if}

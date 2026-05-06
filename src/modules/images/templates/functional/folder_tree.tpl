{* Recursive folder tree - always fully expanded *}
{foreach $folders as $folder}
<div class="fimg-folder" data-folder-id="{$folder.id}">
    <div class="fimg-folder-header">
        <button type="button" class="fimg-folder-toggle">&#9658;</button>
        <span class="fimg-folder-icon">&#128193;</span>
        <a href="{$backend_base_url}&amp;fimg_folder={$folder.id}" class="fimg-folder-name{if $current_folder_id == $folder.id} selected{/if}">{$folder.name|escape}</a>
        <span class="fimg-folder-actions">
            <button type="button" class="fimg-folder-add-image" data-folder-id="{$folder.id}" title="Afbeelding toevoegen in deze map">+&#128247;</button>
            <button type="button" class="fimg-folder-add-subfolder" data-folder-id="{$folder.id}" title="Submap toevoegen">+&#128193;</button>
            <button type="button" class="fimg-folder-delete" data-folder-id="{$folder.id}" data-folder-name="{$folder.name|escape:'html'}" title="Map verwijderen">&#128465;</button>
        </span>
    </div>
    <div class="fimg-folder-children">
        {include file="images/templates/functional/folder_tree.tpl" folders=$folder.sub_folders images=$folder.images depth=$depth+1 current_image_id=$current_image_id current_folder_id=$current_folder_id}
    </div>
</div>
{/foreach}

{foreach $images as $image}
<div class="fimg-image-item{if $current_image_id == $image.id} selected{/if}" data-image-id="{$image.id}" draggable="true">
    <span class="fimg-image-icon">&#128247;</span>
    <a href="{$backend_base_url}&amp;fimg={$image.id}" class="fimg-image-title">{$image.title|escape}</a>
</div>
{/foreach}

{if empty($folders) && empty($images) && $depth == 0}
    <div class="fimg-empty-state">Nog geen functionele afbeeldingen. Gebruik de knoppen hierboven om afbeeldingen of mappen toe te voegen.</div>
{/if}

{* Recursive folder tree for functional image picker - read-only *}
{foreach $folders as $folder}
<div class="fimg-folder" data-folder-id="{$folder.id}">
    <div class="fimg-folder-header" onclick="$(this).closest('.fimg-folder').toggleClass('open');" style="cursor:pointer;">
        <button type="button" class="fimg-folder-toggle" onclick="event.stopPropagation(); $(this).closest('.fimg-folder').toggleClass('open');">&#9658;</button>
        <span class="fimg-folder-icon">&#128193;</span>
        <span class="fimg-folder-name">{$folder.name|escape}</span>
    </div>
    <div class="fimg-folder-children">
        {include file="list_element/templates/fimg_picker_tree.tpl" folders=$folder.sub_folders images=$folder.images}
    </div>
</div>
{/foreach}

{foreach $images as $image}
<div class="fimg-picker-item" data-id="{$image.id}" data-title="{$image.title|escape:'html'}"
     onclick="selectFunctionalImage({$image.id}, '{$image.title|escape:'javascript'}');">
    <span class="fimg-image-icon">&#128247;</span> {$image.title|escape}
</div>
{/foreach}

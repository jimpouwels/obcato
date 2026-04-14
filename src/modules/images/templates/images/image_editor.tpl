<div class="admin_form_v2 image_editor_wrapper">
    <div style="float: left">
        {$size_field}
        {$new_width_field}
        {$new_height_field}
        {$crop_horizontal_field}
        {$crop_vertical_field}
        <input type="hidden" id="{$crop_field_prefix}top" name="{$crop_field_prefix}top" value="0" />
        <input type="hidden" id="{$crop_field_prefix}bottom" name="{$crop_field_prefix}bottom" value="0" />
        <input type="hidden" id="{$crop_field_prefix}left" name="{$crop_field_prefix}left" value="0" />
        <input type="hidden" id="{$crop_field_prefix}right" name="{$crop_field_prefix}right" value="0" />
    </div>
    {if $url}
        <div style="width: fit-content; float: right">
            <div class="image-crop-container" style="position: relative; display: inline-block; cursor: crosshair;">
                <img class="crop-target-image" title="{$title}" alt="{$title}" src="{$url}" style="max-width: 400px; max-height: 400px; display: block;" />
                <div class="crop-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; pointer-events: none;">
                    <div class="crop-area-shadow" style="position: absolute; box-shadow: 0 0 0 9999px rgba(0,0,0,0.5);"></div>
                </div>
                <div class="crop-area" style="position: absolute; outline: 2px dashed #fff; pointer-events: all; cursor: move;">
                    <div class="crop-handle crop-handle-tl" style="position: absolute; top: -5px; left: -5px; width: 10px; height: 10px; background: #fff; border: 1px solid #000; cursor: nw-resize;"></div>
                    <div class="crop-handle crop-handle-tr" style="position: absolute; top: -5px; right: -5px; width: 10px; height: 10px; background: #fff; border: 1px solid #000; cursor: ne-resize;"></div>
                    <div class="crop-handle crop-handle-bl" style="position: absolute; bottom: -5px; left: -5px; width: 10px; height: 10px; background: #fff; border: 1px solid #000; cursor: sw-resize;"></div>
                    <div class="crop-handle crop-handle-br" style="position: absolute; bottom: -5px; right: -5px; width: 10px; height: 10px; background: #fff; border: 1px solid #000; cursor: se-resize;"></div>
                    <div class="crop-handle crop-handle-tc" style="position: absolute; top: -5px; left: 50%; transform: translateX(-50%); width: 10px; height: 10px; background: #fff; border: 1px solid #000; cursor: n-resize;"></div>
                    <div class="crop-handle crop-handle-bc" style="position: absolute; bottom: -5px; left: 50%; transform: translateX(-50%); width: 10px; height: 10px; background: #fff; border: 1px solid #000; cursor: s-resize;"></div>
                    <div class="crop-handle crop-handle-lc" style="position: absolute; left: -5px; top: 50%; transform: translateY(-50%); width: 10px; height: 10px; background: #fff; border: 1px solid #000; cursor: w-resize;"></div>
                    <div class="crop-handle crop-handle-rc" style="position: absolute; right: -5px; top: 50%; transform: translateY(-50%); width: 10px; height: 10px; background: #fff; border: 1px solid #000; cursor: e-resize;"></div>
                </div>
            </div>
            <div class="crop-center-buttons">
                <button type="button" class="crop-center-btn crop-center-h" title="Centreer horizontaal">&#8596; Centreer horizontaal</button>
                <button type="button" class="crop-center-btn crop-center-v" title="Centreer verticaal">&#8597; Centreer verticaal</button>
            </div>
        </div>
    {else}
        <span><em>{$text_resources.images_image_editor_no_image_found_yet_message}</em></span>
    {/if}
    {if isset($reset_button)}
        <div style="clear: both">
            {$reset_button}
        </div>
    {/if}

</div>
{* Link picker modal - rendered once, reused via JS openLinkPickerModal(callback) *}
<div id="link-picker-modal" class="link-picker-modal" style="display:none;">
    <div class="link-picker-backdrop"></div>
    <div class="link-picker-content">
        <div class="link-picker-header">
            <h3>Link selecteren</h3>
            <button type="button" class="close-btn" id="link-picker-close">&times;</button>
        </div>
        <div class="link-picker-search-bar">
            <input type="text" id="link-picker-search" class="link-picker-search-input" placeholder="Zoek op naam of URL..." autocomplete="off" />
        </div>
        <div class="link-picker-body" id="link-picker-body">
            <div class="link-picker-loading"><div class="ajax-spinner"><span></span></div></div>
        </div>
        <div class="link-picker-footer">
            <button type="button" class="link-editor-btn link-editor-btn-cancel" id="link-picker-cancel">Annuleren</button>
        </div>
    </div>
</div>

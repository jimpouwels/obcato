{* Link editor dialog - rendered once and reused via JavaScript *}
<div id="link-editor-dialog" class="link-editor-modal" style="display: none;">
    <div class="link-editor-backdrop"></div>
    <div class="link-editor-content">
        <div class="link-editor-header">
            <h3 id="link-editor-title">Link bewerken</h3>
        </div>
        <div class="link-editor-body">
            <div class="form-field-group">
                <label for="link-editor-text">Tekst:</label>
                <input type="text" id="link-editor-text" class="admin_field" placeholder="Link tekst">
            </div>
            
            <div class="form-field-group">
                <label>Link naar:</label>
                <div class="link-type-tabs">
                    <button type="button" class="link-type-tab active" data-tab="url">URL</button>
                    <button type="button" class="link-type-tab" data-tab="page">Pagina</button>
                    <button type="button" class="link-type-tab" data-tab="article">Artikel</button>
                </div>
            </div>
            
            <div class="link-type-content" id="link-type-url">
                <div class="form-field-group" id="link-url-input-group">
                    <label for="link-editor-url">URL:</label>
                    <input type="text" id="link-editor-url" class="admin_field" placeholder="https://">
                    <button type="button" class="link-add-btn" id="link-url-add">Toevoegen</button>
                </div>
                <div class="link-selected-item" id="link-url-selected" style="display: none;">
                    <span class="selected-item-name"></span>
                    <button type="button" class="clear-selection">&times;</button>
                </div>
            </div>
            
            <div class="link-type-content" id="link-type-page" style="display: none;">
                <div class="form-field-group">
                    <label for="link-page-search">Zoek pagina:</label>
                    <input type="text" id="link-page-search" class="admin_field" placeholder="Typ om te zoeken...">
                </div>
                <div class="link-search-results" id="link-page-results"></div>
                <div class="link-selected-item" id="link-page-selected" style="display: none;">
                    <span class="selected-item-name"></span>
                    <button type="button" class="clear-selection">&times;</button>
                </div>
            </div>
            
            <div class="link-type-content" id="link-type-article" style="display: none;">
                <div class="form-field-group">
                    <label for="link-article-search">Zoek artikel:</label>
                    <input type="text" id="link-article-search" class="admin_field" placeholder="Typ om te zoeken...">
                </div>
                <div class="link-search-results" id="link-article-results"></div>
                <div class="link-selected-item" id="link-article-selected" style="display: none;">
                    <span class="selected-item-name"></span>
                    <button type="button" class="clear-selection">&times;</button>
                </div>
            </div>
            
            <div class="form-field-group link-options-group">
                <label>
                    <input type="checkbox" id="link-new-tab" checked>
                    Open in nieuw tabblad
                </label>
            </div>
        </div>
        <div class="link-editor-footer">
            <button type="button" class="link-editor-btn link-editor-btn-delete" id="link-editor-delete" style="float: left;">
                Verwijderen
            </button>
            <button type="button" class="link-editor-btn link-editor-btn-cancel" id="link-editor-cancel">
                Annuleren
            </button>
            <button type="button" class="link-editor-btn link-editor-btn-save" id="link-editor-save">
                Opslaan
            </button>
        </div>
    </div>
</div>

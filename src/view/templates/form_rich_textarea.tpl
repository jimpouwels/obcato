<div class="rich-text-editor-wrapper">
    <div class="rich-text-content {$classes}"
         contenteditable="true"
         id="{$field_name}_editor"
         data-field-name="{$field_name}">{if isset($html_value)}{$html_value}{/if}</div>
    <div class="rich-text-toolbar">
        <button type="button" class="rich-text-btn" data-command="bold" title="Bold (Ctrl+B)">
            <strong>B</strong>
        </button>
        <button type="button" class="rich-text-btn" data-command="italic" title="Italic (Ctrl+I)">
            <em>I</em>
        </button>
        <button type="button" class="rich-text-btn" data-command="underline" title="Underline (Ctrl+U)">
            <u>U</u>
        </button>
        <span class="toolbar-separator"></span>
        <button type="button" class="rich-text-btn" data-command="createLink" title="Insert Link">
            🔗
        </button>
    </div>
    <textarea name="{$field_name}"
              id="{$field_name}"
              style="display: none;">{if isset($html_value)}{$html_value}{/if}</textarea>
</div>

<div class="webforms_editor_add_buttons">
    {$button_add_textfield}
    {$button_add_textarea}
    {$button_add_button}
</div>

<div class="webforms_editor_form_fields draggable_items">
    {foreach from=$form_fields item=form_field}
        {$form_field}
    {/foreach}
</div>
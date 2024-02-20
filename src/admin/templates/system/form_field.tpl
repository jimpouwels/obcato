<div class="admin_form_field_v2">
    {if $error}
        <div class="admin_form_error">
            {$error}
        </div>
    {/if}
    <div class="admin_label_wrapper">
        {if isset($label)}
            {$label}
        {/if}
    </div>
    <div class="admin_field_wrapper admin_field_wrapper-{$type}">
        {$form_field}
    </div>
</div>
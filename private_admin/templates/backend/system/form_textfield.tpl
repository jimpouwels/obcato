{assign var='visible_attr' value='text'}
{if !$is_visible}
    {assign var='visible_attr' value='hidden'}
{/if}
<input type="{$visible_attr}" value="{$field_value}" id="{$field_name}" name="{$field_name}" class="admin_field {$classes}">
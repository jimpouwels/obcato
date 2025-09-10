<select name="{$field_name}" id="{$field_name}" class="admin_field {$classes}">
    {if $include_select_indication}
        <option value="">{$text_resources.select_field_default_text}</option>
    {/if}
    {foreach from=$options item=value}
        {assign var='selected' value=''}
        {if {$value.value} == {$field_value}}
            {assign var='selected' value='selected="selected"'}
        {/if}
        <option {$selected} value="{$value.value}">{$value.name}</option>
        {assign var='selected' value=''}
    {/foreach}
</select>
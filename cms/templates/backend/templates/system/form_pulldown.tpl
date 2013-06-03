{$error}
{$label}
<select name="{$field_name}" id="{$field_name}" class="{$classes}">
	{foreach from=$options item=value}
		{assign var='selected' value=''}
		{if {$value.value} == {$field_value}}
			{assign var='selected' value='selected="selected"'}
		{/if}
		<option {$selected} value="{$value.value}">{$value.name}</option>
		{assign var='selected' value=''}
	{/foreach}
</select>
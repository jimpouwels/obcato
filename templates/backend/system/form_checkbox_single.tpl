{$error}
{$label}
<input class="admin_field_checkbox {$classes}" 
	   type="checkbox" 
	   name="{$field_name}" 
	   id="{$field_name}" 
	   {if $field_value == 'on' || $field_value == '1'}
			checked="checked"
	   {/if}
/>

<form action="{$backend_base_url}&user={$user_id}" method="post" id="user_form" name="update_user">
	<input type="hidden" name="user_id" value="{$user_id}" />
	<input type="hidden" id="action" name="action" value="" />

	<ul class="admin_form">
		<li>{$username_field}</li>
		<li>{$firstname_field}</li>
		<li>{$prefix_field}</li>
		<li>{$lastname_field}</li>
		<li>{$email_field}</li>
		{if isset($new_password_first) && isset($new_password_second)}
			<li>{$new_password_first}</li>
			<li>{$new_password_second}</li>
		{/if}
	</ul>
</form>

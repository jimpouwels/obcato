<div class="settings_form">
	
	<form action="/admin/index.php" method="post" id="settings_form">
		<fieldset class="admin_fieldset">
			<div class="fieldset-title">Algemene instellingen</div>
			
			<ul class="admin_form">
				<li>{$website_title}</li>
				<li>{$email_field}</li>
				<li>
					<table cellpadding="0" cellspacing="0">
						<tr>
							<td>
								{$homepage_picker}
							</td>
							{if !is_null($current_homepage_id)}
								<td><em><a class="link" href="/admin/index.php?module_id=3&amp;page={$current_homepage_id}">{$current_homepage_title}</a></em></td>
							{/if}
						</tr>
					</table>
				</li>
			</ul>
		</fieldset>
		<fieldset class="admin_fieldset">
			<div class="fieldset-title">Directory instellingen</div>
			
			<ul class="admin_form">
                <li>{$root_dir}</li>
				<li>{$static_dir}</li>
				<li>{$config_dir}</li>
				<li>{$upload_dir}</li>
				<li>{$frontend_template_dir}</li>
				<li>{$backend_template_dir}</li>
				<li>{$component_dir}</li>
			</ul>
		</fieldset>
		<fieldset class="admin_fieldset">
			<div class="fieldset-title">Domein instellingen</div>
			<ul class="admin_form">
				<li>{$frontend_hostname}</li>
				<li>{$backend_hostname}</li>
				<li>{$smtp_host}</li>
			</ul>
		</fieldset>
	</form>
</div>
<form method="post" id="update_target_page_form" action="/admin/index.php">
	<fieldset class="admin_fieldset target_pages_fieldset">
		<div class="fieldset-title">Beschikbare doelpagina's</div>
		
		<input type="hidden" id="change_default_value" name="change_default_value" />
		<input type="hidden" name="action" id="action" value="update_article_option" />
		
		<a href="#" id="update_article_options" class="displaynone"></a>
		
		{if !is_null($target_pages) && count($target_pages) > 0}
			<table cellspacing="0" cellpadding="5" border="0" class="targetpage-table">
				<colgroup width="225px"></colgroup>
				<colgroup width="50px"></colgroup>
				<colgroup width="20px"></colgroup>
				<thead>
					<tr class="header">
						<th>Paginatitel</th>
						<th class="center_column">Standaard</th>
						<th class="center_column">Verwijder</th>
					</tr>
				</thead>
				<tbody>	
					{foreach from=$target_pages item=target_page}			
						<tr>
							<td>{$target_page.title}</td>
							<td class="center_column">
								{assign var='render_link' value=false}
								{if $target_page.id == $default_target_page.id}
									{assign var='icon' value='green_flag.png'}
									{assign var='txt' value='Standaard'}
								{else}
									{assign var='icon' value='red_flag.png'}
									{assign var='txt' value='Maak standaard'}
									{assign var='render_link' value=true}
								{/if}
								
								{if $render_link}
									<a title="{$txt}" onclick="changeDefaultTargetPage({$target_page.id});" href="#">
								{/if}
								<img alt="{$txt}" src="/admin/static.php?static=/default/img/default_icons/{$icon}">
								{if $render_link}
									</a>
								{/if}
							</td>
							<td class="delete_column center_column">
								<label for="target_page_{$target_page.id}_delete" class="admin_label"></label>
								<input type="checkbox" id="target_page_{$target_page.id}_delete" name="target_page_{$target_page.id}_delete" class="admin_field_checkbox" />
							</td>
						</tr>
					{/foreach}
				</tbody>
			</table>
		{/if}
		
		{$page_picker}
	</fieldset>
</form>
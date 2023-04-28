<div class="image_search_wrapper">
	<form id="image_search" class="image_search_form" action="{$backend_base_url}" method="get">
		<ul class="admin_form">
			<li class="displaynone">
				{$module_id_form_field}
				{$module_tab_id_form_field}
				<input type="hidden" name="action" value="search" />
			</li>
			<li>{$title_search_field}</li>
			<li>{$filename_search_field}</li>
			<li>{$labels_search_field}</li>
		</ul>
		<div class="button_container">
			{$search_button}
		</div>
		<div class="show_all_link">
			<a href="{$backend_base_url}" title="{$text_resources.images_search_show_all}">{$text_resources.images_search_show_all}</a>
		</div>
		<div class="show_all_link">
			<a href="{$backend_base_url}&no_labels=true" title="{$text_resources.images_search_show_all_without_label}">{$text_resources.images_search_show_all_without_label}</a>
		</div>
	</form>
</div>
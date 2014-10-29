<fieldset class="admin_fieldset image_search">
	<div class="fieldset-title">Zoeken</div>
	
	<form id="image_search" action="/admin/index.php" method="get">
		<ul class="admin_form">
			<li class="displaynone">
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
			<a href="/admin/index.php" title="Toon alle afbeeldingen">Toon allen</a>
		</div>
		<div class="show_all_link">
			<a href="/admin/index.php?no_labels=true" title="Toon alle afbeeldingen zonder label">Toon allen zonder label</a>
		</div>
	</form>
</fieldset>
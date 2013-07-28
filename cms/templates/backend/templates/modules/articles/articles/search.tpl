<fieldset class="admin_fieldset article_search">
	<div class="fieldset-title">Zoeken</div>
	
	<form id="article_search" action="/admin/index.php" method="get">
		<ul class="admin_form">
			<li class="displaynone">
				<input type="hidden" name="action" value="search" />
			</li>
			<li>{$search_query_field}</li>
			<li>{$term_query_field}</li>
		</ul>
		<div class="button_container">
			{$search_button}
		</div>
		<div class="show_all_link">
			<a href="/admin/index.php" title="Toon alle artikelen">Toon allen</a>
		</div>
	</form>
</fieldset>
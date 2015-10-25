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
		<a href="/admin/index.php" title="Toon alle artikelen">{$text_resources.articles_search_box_show_all}</a>
	</div>
</form>

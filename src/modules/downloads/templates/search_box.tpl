<form id="download_search" action="{$backend_base_url}" method="get">
    <ul class="admin_form">
        <li class="displaynone">
            <input type="hidden" name="action" value="search" />
        </li>
        <li>{$search_query_field}</li>
    </ul>
    <div class="button_container">
        {$search_button}
    </div>
    <div class="show_all_link">
        <a href="{$backend_base_url}" title="Toon alle downloads">Toon allen</a>
    </div>
</form>

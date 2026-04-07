<form id="article_search" action="{$backend_base_url_raw}" method="get">
    <div class="admin_form_v2">
        <div class="displaynone">
            {$module_id_form_field}
            {$module_tab_id_form_field}
            <input type="hidden" name="action" value="search" />
        </div>
        {$search_query_field}
        {$term_query_field}
    </div>
    <div class="button_container">
        {$search_button}
    </div>
</form>

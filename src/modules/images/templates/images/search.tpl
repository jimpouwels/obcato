<div class="image_search_wrapper">
    <form id="image_search" class="image_search_form" action="{$backend_base_url}" method="get">
        <div class="admin_form_v2">
            <div class="displaynone">
                {$module_id_form_field}
                {$module_tab_id_form_field}
                <input type="hidden" name="action" value="search" />
            </div>
            {$title_search_field}
            {$filename_search_field}
        </div>
        <div class="button_container">
            {$search_button}
        </div>
    </form>
</div>
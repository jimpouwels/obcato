<form id="search_form" method="get">
    <div class="popup_search_container">
        <input type="hidden" name="object" value="{$search_object}" />
        <input type="hidden" name="backfill" value="{$backfill}" />
        <input type="hidden" name="back_click_id" value="{$back_click_id}" />
        <input type="hidden" name="popup" value="{$popup_type}" />

        <div class="admin_form_v2">
            {$search_field}
            {$search_options}
        </div>

        {$search_button}
    </div>

    <div class="popup_search_results_container">
        {if count($search_results) > 0}
            <div class="popup_search_results_list">
                {foreach from=$search_results item=search_result}
                    <div class="popup_search_result_item">
                        <a href="#"
                           onclick="submitSelectionBackToOpener('{$backfill}', {$search_result.id}, '{$back_click_id}'); return false;"
                           title="Selecteer">
                            <span class="result_name">{$search_result.name}</span>
                        </a>
                    </div>
                {/foreach}
            </div>
        {else}
            {$no_results_message}
        {/if}
    </div>
</form>

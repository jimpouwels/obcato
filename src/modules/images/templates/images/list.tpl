{if $search_results}
    <form action="{$backend_base_url}" method="post" id="toggle_image_published_form" enctype="multipart/form-data">
        <input type="hidden" id="{$action_form_id}" name="{$action_form_id}" value="" />
        <input type="hidden" id="image_id" name="image_id" value="" />
    </form>
    {if $current_search_title}
        <p class="article_list_searched_by_text">
            <strong><em>Zoekterm: </em></strong>&nbsp;'{$current_search_title}'
        </p>
    {/if}
    {if $current_search_filename}
        <p class="article_list_searched_by_text">
            <strong><em>Bestandsnaam: </em></strong>&nbsp;'{$current_search_filename}'
        </p>
    {/if}
    {if $current_search_label}
        <p class="article_list_searched_by_text">
            <strong><em>Label: </em></strong>&nbsp;'{$current_search_label}'</p>
        </p>
    {/if}
    <table class="listing" width="95%" cellspacing="0" cellpadding="5" border="0">
        <colgroup width="50"></colgroup>
        <colgroup width="200px"></colgroup>
        <colgroup width="50px"></colgroup>
        <colgroup width="50px"></colgroup>
        <colgroup width="10px"></colgroup>
        <thead>
        <tr>
            <th>{$text_resources.images_list_thumbnail_column}</th>
            <th>{$text_resources.images_list_title_column}</th>
            <th>{$text_resources.images_list_created_at_column}</th>
            <th>{$text_resources.images_list_created_by_column}</th>
            <th>{$text_resources.images_list_published_column}</th>
            <th>{$text_resources.images_list_has_mobile_version_column}</th>
        </tr>
        </thead>
        <tbody>
        {foreach from=$search_results item=search_result}
            <tr>
                <td><img title="{$search_result.title}" src="{$search_result.thumb}" alt="{$search_result.title}" /></td>
                <td><a href="{$backend_base_url}&image={$search_result.id}"
                       title="{$search_result.title}">{$search_result.title}</a></td>
                <td>{$search_result.created_at}</td>
                <td>{$search_result.created_by}</td>
                <td>
                    <a onclick="toggleImagePublished('{$search_result.id}'); return false;" href="#">
                        {if $search_result.published}
                        <img alt="Publiceren"
                             src="/admin?file=/default/img/default_icons/green_flag.png" /></a>
                    {else}
                    <img alt="Depubliceren" src="/admin?file=/default/img/default_icons/red_flag.png" />
                    {/if}
                    </a>
                </td>
                <td>
                    {if $search_result.has_mobile_version}
                        <img src="/admin?file=/default/img/default_icons/green_flag.png" />
                    {else}
                        <img src="/admin?file=/default/img/default_icons/red_flag.png" />
                    {/if}
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
    <br/>
{else}
    {$no_results_message}
{/if}

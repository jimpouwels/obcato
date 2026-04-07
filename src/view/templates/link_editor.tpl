<input type="hidden" id="delete_link_target" name="delete_link_target" value="" />

<div class="link-editor-container">
    {if isset($links) > 0}
        <table class="link-table">
            <thead>
            <tr>
                <th></th>
                <th>{$text_resources.link_title}</th>
                <th>{$text_resources.link_target}</th>
                <th>{$text_resources.link_code}</th>
                <th>{$text_resources.link_open_in}</th>
                <th></th>
                <th>{$text_resources.link_delete}</th>
            </tr>
            </thead>
            <tbody>
            {foreach from=$links item=link}
                <tr>
                    <td class="link-addlink">
                        <a href="#" onclick="putLink('{$link.id}'); return false;" title="{$text_resources.place_link}">
                            <img src="/admin?file=/default/img/default_icons/place_link.png"
                                 alt="{$text_resources.place_link}" />
                        </a>
                    </td>
                    <td class="link-title">
                        {$link.title_field}
                    </td>
                    <td class="link-target">
                        {if !is_null($link.target_field)}
                            {$link.target_field}
                        {else}
                            <div class="link-target-display">
                                <span class="target-title">{$link.target_title}</span>
                                <button type="button" class="delete-target-btn" onclick="deleteLink('{$link.id}'); return false;" title="Verwijder linkdoel">
                                    <img src="/admin?file=/default/img/default_icons/delete_small.png" alt="Verwijder linkdoel" />
                                </button>
                            </div>
                        {/if}
                    </td>
                    <td class="link-code">
                        {$link.code_field}
                    </td>
                    <td class="link-target">
                        {$link.target_screen_field}
                    </td>
                    <td class="link-button">
                        {$link.element_holder_picker}
                    </td>
                    <td class="link-delete">
                        {$link.delete_field}
                    </td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    {/if}
    
    <div class="link-add-button">
        <button type="button" class="add-link-btn" onclick="addLink(); return false;" title="Link toevoegen">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                <path d="M8 3V13M3 8H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span>Link toevoegen</span>
        </button>
    </div>
</div>

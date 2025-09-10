<div id="navigation-menu">
    <ul id="menu">
        {foreach from=$groups item=group}
            <li class="module-group">
                <a href="#" class="parent" onclick="return false;">{$group.title}</a>
                <div class="submenu">
                    <ul>
                        {if isset($group.elements)}
                            {foreach from=$group.elements item=element}
                                <li>
                                    <img src="{$element.icon_url}" alt="{$element.name}" />
                                    <a href="#"
                                       onclick="addElement('{$element.id}', '{$text_resources.navigation_menu_add_element_error}'); return false;">{$element.name}</a>
                                </li>
                            {/foreach}
                            <li class="last">
                                <img alt="Link toevoegen"
                                     src="/admin/static.php?file=/default/img/element_icons/link.png" />
                                <a href="#" onclick="addLink(); return false;">Link</a>
                            </li>
                        {else}
                            {foreach from=$group.modules item=module}
                                <li{if $module.last} class="last"{/if}>
                                    <img src="{$module.icon_url}" alt="{$module.title}" />
                                    <a {if {$module.popup}}onclick="window.open('/admin/popup_entity.php?module_id={$module.id}','{$module.title}','width=640,height=480'); return false;"{/if}
                                       href="{$backend_base_url_raw}?module_id={$module.id}">{$module.title}</a>
                                </li>
                            {/foreach}
                        {/if}
                    </ul>
                </div>
            </li>
        {/foreach}
    </ul>
</div>
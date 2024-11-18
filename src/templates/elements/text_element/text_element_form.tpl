<div class="admin_form_v2">
    {$title_field}
    {$text_field}
    <label for="element_{$id}_link">{$text_resources.text_element_editor_link}:</label>
    <select name="element_{$id}_link" id="element_{$id}_link">
        {foreach from=$link_options item=link}
            <option selected="selected" value="{$link.value}">{$link.name}</option>
        {/foreach}
    </select>
    <a href="#" onclick="putLink($('#element_{$id}_link').find(':selected').val()); return false;" title="{$text_resources.place_link}">
        <img src="/admin/static.php?file=/default/img/default_icons/place_link.png"
             alt="{$text_resources.place_link}"/>
    </a>
</div>
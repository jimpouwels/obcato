<fieldset class="admin_fieldset install-form-fieldset">
    <div class="fieldset-title">Instaleer component</div>
    <form id="install_component_form" action="/admin/index.php" method="POST" enctype="multipart/form-data">
        <input id="action" name="action" value="" type="hidden" />
        {$upload_field}
    </form>
</fieldset>
{if count($log_messages) > 0}
    <fieldset class="admin_fieldset installation-log-fieldset">
        <div class="fieldset-title">Installatie log</div>
        <div class="install-log">
            {foreach from=$log_messages item=message}
                {$message}<br />
            {/foreach}
        </div>
    </fieldset>
{/if}
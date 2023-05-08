<form method="post">
    <input type="hidden" name="webform_id" value="{$webform_id}" />
    <input type="hidden" id="captcha_token" name="captcha_token" value="" />
    {$form_html}
</form>
{if isset($captcha_key)}
    <script src="https://www.google.com/recaptcha/api.js?render={$captcha_key}"></script>
    <script type="text/javascript">
        grecaptcha.ready(function() {
            grecaptcha.execute('{$captcha_key}', { action: 'submit' } ).then(function(token) {
                console.log
                document.getElementById('captcha_token').value = token;
            } );
        } );
    </script>
{/if}
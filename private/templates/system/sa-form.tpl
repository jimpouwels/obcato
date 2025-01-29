<form method="post" onsubmit="setCaptchaToken()">
    <input type="hidden" name="webform_id" value="{$webform_id}" />
    <input type="hidden" id="captcha_token" name="captcha_token" value="" />
    {$form_html}
</form>
{if isset($captcha_key)}
    <script src="https://www.google.com/recaptcha/enterprise.js?render={$captcha_key}"></script>
    <script type="text/javascript">
        $(document).ready(() => {
            grecaptcha.enterprise.ready(async () => {
                document.getElementById('captcha_token').value = await grecaptcha.enterprise.execute("{$captcha_key}", { action: 'SUBMIT_FORM' } );
            });
        });
    </script>
{/if}

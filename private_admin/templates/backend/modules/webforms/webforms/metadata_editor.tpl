<ul class="admin_form">
	<li>{$title_field}</li>
	<li>{$include_captcha_field}</li>
	<li {if !$include_captcha}class="displaynone"{/if} class="{$captcha_key_field_class}">{$captcha_key_field}</li>
	<li {if !$include_captcha}class="displaynone"{/if} class="{$captcha_key_field_class}">{$captcha_secret_field}</li>
</ul>
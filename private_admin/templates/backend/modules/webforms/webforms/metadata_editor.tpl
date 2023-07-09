<div class="admin_form_v2">
	{$title_field}
	{$template_picker}
	{$include_captcha_field}
	<div class="{$captcha_key_field_class}{if !$include_captcha} displaynone"{/if}">{$captcha_key_field}</div>
	<div class="{$captcha_key_field_class}{if !$include_captcha} displaynone"{/if}">{$captcha_secret_field}</div>
</div>
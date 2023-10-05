<?php
require_once CMS_ROOT . "/modules/settings/model/Settings.php";

$website_settings = Settings::find();

// EDITOR FORM CONSTANTS
define("ADD_ELEMENT_FORM_ID", 'add_element_type_id');
define("EDIT_ELEMENT_HOLDER_ID", 'element_holder_id');
define("ACTION_FORM_ID", 'action');
define("DELETE_ELEMENT_FORM_ID", 'delete_element');
define('ELEMENT_HOLDER_FORM_ID', 'element_holder_form_id');
define('ELEMENT_ORDER_ID', 'element_order');

// ELEMENT HOLDER TYPES
define("ELEMENT_HOLDER_PAGE", 'ELEMENT_HOLDER_PAGE');
define("ELEMENT_HOLDER_ARTICLE", 'ELEMENT_HOLDER_ARTICLE');
define("ELEMENT_HOLDER_BLOCK", 'ELEMENT_HOLDER_BLOCK');

// DEFINE SYSTEM VERSION
define("SYSTEM_VERSION", "1.0.0.5");
define("DB_VERSION", $website_settings->getDatabaseVersion());

// DEFINE TIME OUT
define("SESSION_TIMEOUT", 3600);

// DIRECTORIES
define("COMPONENT_DIR", PRIVATE_DIR . '/components');
define("COMPONENT_TEMP_DIR", COMPONENT_DIR . '/temp');
define("UPLOAD_DIR", PRIVATE_DIR . '/upload');
define("FRONTEND_TEMPLATE_DIR", PRIVATE_DIR . '/templates');
define("BACKEND_TEMPLATE_DIR", CMS_ROOT . '/templates');
define("STATIC_DIR", CMS_ROOT . '/static');
define("CONFIG_DIR", PRIVATE_DIR . '/config');

// OTHER
define("WEBSITE_TITLE", $website_settings->getWebsiteTitle());

<?php

namespace Obcato\Core;

// EDITOR FORM CONSTANTS
use const Obcato\CMS_ROOT;

const ADD_ELEMENT_FORM_ID = 'add_element_type_id';
const EDIT_ELEMENT_HOLDER_ID = 'element_holder_id';
const ACTION_FORM_ID = 'action';
const DELETE_ELEMENT_FORM_ID = 'delete_element';
const ELEMENT_HOLDER_FORM_ID = 'element_holder_form_id';
const ELEMENT_ORDER_ID = 'element_order';

// ELEMENT HOLDER TYPES
const ELEMENT_HOLDER_PAGE = 'ELEMENT_HOLDER_PAGE';
const ELEMENT_HOLDER_ARTICLE = 'ELEMENT_HOLDER_ARTICLE';
const ELEMENT_HOLDER_BLOCK = 'ELEMENT_HOLDER_BLOCK';

// DEFINE SYSTEM VERSION
const SYSTEM_VERSION = "1.0.0.5";

// DEFINE TIME OUT
const SESSION_TIMEOUT = 3600;

// DIRECTORIES
const COMPONENT_DIR = PRIVATE_DIR . '/components';
const COMPONENT_TEMP_DIR = COMPONENT_DIR . '/temp';
const UPLOAD_DIR = PRIVATE_DIR . '/upload';
const FRONTEND_TEMPLATE_DIR = PRIVATE_DIR . '/templates';
const BACKEND_TEMPLATE_DIR = CMS_ROOT . '/templates';
const STATIC_DIR = CMS_ROOT . '/static';
const CONFIG_DIR = PRIVATE_DIR . '/config';
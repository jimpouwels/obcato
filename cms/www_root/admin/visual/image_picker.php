<?php	// No direct access	defined('_ACCESS') or die;		require_once "visual/object_picker.php";	require_once "libraries/system/template_engine.php";		class ImagePicker extends ObjectPicker {				public function __construct($label, $value, $backing_field_id, $button_label, $opener_submit_id, $button_id) {			parent::__construct($label, $value, $backing_field_id, $button_label, $opener_submit_id, $button_id);		}				public function getType() {			return Search::$IMAGES;		}		}?>
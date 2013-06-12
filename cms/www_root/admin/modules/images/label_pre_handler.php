<?php	// No direct access	defined('_ACCESS') or die;		require_once "dao/image_dao.php";	require_once "core/data/image_label.php";	require_once "core/http/module_request_handler.php";		class LabelPreHandler extends ModuleRequestHandler {		private static $LABEL_QUERYSTRING_KEY = "label";			private $_image_dao;		private $_current_label;			public function __construct() {			$this->_image_dao = ImageDao::getInstance();		}			public function handleGet() {			$this->_current_label = $this->getCurrentLabelFromGetRequest();					}				public function handlePost() {			$this->_current_label = $this->getCurrentLabelFromPostRequest();			if ($this->isUpdateLabelAction())				$this->updateLabel();			else if ($this->isAddLabelAction())				$this->addLabel();			else if ($this->isDeleteLabelsAction())				$this->deleteLabels();		}				public function getCurrentLabel() {			return $this->_current_label;		}		private function getCurrentLabelFromGetRequest() {			$current_label = null;			if (isset($_GET[self::$LABEL_QUERYSTRING_KEY])) {				$label_id = $_GET[self::$LABEL_QUERYSTRING_KEY];				$current_label = $this->_image_dao->getLabel($label_id);			}			return $current_label;		}				private function getCurrentLabelFromPostRequest() {			$current_label = null;			if (isset($_POST["label_id"]) && $_POST["label_id"] != "") {				$current_label = $this->_image_dao->getLabel($_POST["label_id"]);			}			return $current_label;		}				private function addLabel() {			$label = new ImageLabel();			$label->setName("Nieuw label");			$this->_image_dao->persistLabel($label);			header("Location: /admin/index.php?label=" . $label->getId());		}				private function isUpdateLabelAction() {			return isset($_POST["action"]) && $_POST["action"] == "update_label";		}				private function isDeleteLabelsAction() {			return isset($_POST["label_delete_action"]) && $_POST["label_delete_action"] == "delete_labels";		}				private function isAddLabelAction() {			return isset($_POST["add_label_action"]) && $_POST["add_label_action"] != "";		}				private function updateLabel() {			global $errors;			$name = FormValidator::checkEmpty("name", "Titel is verplicht");						if ($name != "") {				$existing_label = $this->_image_dao->getLabelByName($name);								if (!is_null($existing_label) && !(isset($_GET["label"]) && $_GET["label"] == $existing_label->getId())) {					$errors["name_error"] = "Er bestaat al een label met deze naam";				}			}						if (count($errors) == 0) {				if (isset($_POST["label_id"]) && $_POST["label_id"] != "") {					$current_label = $this->_image_dao->getLabel($_POST["label_id"]);					$current_label->setName($name);					$this->_image_dao->updateLabel($current_label);					Notifications::setSuccessMessage("Label succesvol opgeslagen");				} else if (isset($_GET["new_label"])) {					// create new label					$new_label = $this->_image_dao->createLabel();					$new_label->setName($name);					$this->_image_dao->updateLabel($new_label);					Notifications::setSuccessMessage("Label succesvol aangemaakt");					header("Location: /admin/index.php?label=" . $new_label->getId());					exit();				}			} else {				Notifications::setFailedMessage("Label niet opgeslagen, verwerk de fouten");			}		}				private function deleteLabels() {		$labels = $this->_image_dao->getAllLabels();		foreach ($labels as $label) {			if (isset($_POST["label_" . $label->getId() . "_delete"])) {				$this->_image_dao->deleteLabel($label);			}		}		Notifications::setSuccessMessage("Label(s) succesvol verwijderd");	}			}	?>
<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "database/dao/image_dao.php";
    require_once CMS_ROOT . "core/model/image_label.php";
    require_once CMS_ROOT . "request_handlers/http_request_handler.php";
    require_once CMS_ROOT . "modules/images/label_form.php";
    
    class LabelRequestHandler extends HttpRequestHandler {

        private static string $LABEL_QUERYSTRING_KEY = "label";
    
        private ImageDao $_image_dao;
        private ?ImageLabel $_current_label;
    
        public function __construct() {
            $this->_image_dao = ImageDao::getInstance();
        }
    
        public function handleGet(): void {
            $this->_current_label = $this->getCurrentLabelFromGetRequest();            
        }
        
        public function handlePost(): void {
            $this->_current_label = $this->getCurrentLabelFromPostRequest();
            if ($this->isUpdateLabelAction()) {
                $this->updateLabel();
            } else if ($this->isAddLabelAction()) {
                $this->addLabel();
            } else if ($this->isDeleteLabelsAction()) {
                $this->deleteLabels();
            }
        }
        
        public function getCurrentLabel(): ?ImageLabel {
            return $this->_current_label;
        }

        private function getCurrentLabelFromGetRequest(): ?ImageLabel {
            $current_label = null;
            if (isset($_GET[self::$LABEL_QUERYSTRING_KEY])) {
                $label_id = $_GET[self::$LABEL_QUERYSTRING_KEY];
                $current_label = $this->_image_dao->getLabel($label_id);
            }
            return $current_label;
        }
        
        private function getCurrentLabelFromPostRequest(): ?ImageLabel {
            $current_label = null;
            if (isset($_POST["label_id"]) && $_POST["label_id"] != "") {
                $current_label = $this->_image_dao->getLabel($_POST["label_id"]);
            }
            return $current_label;
        }
        
        private function addLabel(): void {
            $label = $this->_image_dao->createLabel();
            $label->setName("Nieuw label");
            $this->redirectTo($this->getBackendBaseUrl() . "&label=" . $label->getId());
        }
        
        private function updateLabel(): void {
            $label_form = new LabelForm($this->_current_label);
            try {
                $label_form->loadFields();
                $this->_image_dao->updateLabel($this->_current_label);
                $this->sendSuccessMessage("Label succesvol opgeslagen");
            } catch (FormException $e) {
                $this->sendErrorMessage("Label niet opgeslagen, verwerk de fouten");
            }
        }
        
        private function deleteLabels(): void {
            $labels = $this->_image_dao->getAllLabels();
            foreach ($labels as $label) {
                if (isset($_POST["label_" . $label->getId() . "_delete"])) {
                    $this->_image_dao->deleteLabel($label);
                }
            }
            $this->sendSuccessMessage("Label(s) succesvol verwijderd");
        }
        
        private function isUpdateLabelAction(): bool {
            return isset($_POST["action"]) && $_POST["action"] == "update_label";
        }
        
        private function isDeleteLabelsAction(): bool {
            return isset($_POST["label_delete_action"]) && $_POST["label_delete_action"] == "delete_labels";
        }
        
        private function isAddLabelAction(): bool {
            return isset($_POST["add_label_action"]) && $_POST["add_label_action"] != "";
        }
        
    }
    
?>
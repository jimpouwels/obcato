<?php

namespace Obcato\Core;

class LabelRequestHandler extends HttpRequestHandler {

    private static string $LABEL_QUERYSTRING_KEY = "label";

    private ImageDao $imageDao;
    private ?ImageLabel $currentLabel;

    public function __construct() {
        $this->imageDao = ImageDaoMysql::getInstance();
    }

    public function handleGet(): void {
        $this->currentLabel = $this->getCurrentLabelFromGetRequest();
    }

    public function handlePost(): void {
        $this->currentLabel = $this->getCurrentLabelFromPostRequest();
        if ($this->isUpdateLabelAction()) {
            $this->updateLabel();
        } else if ($this->isAddLabelAction()) {
            $this->addLabel();
        } else if ($this->isDeleteLabelsAction()) {
            $this->deleteLabels();
        }
    }

    public function getCurrentLabel(): ?ImageLabel {
        return $this->currentLabel;
    }

    private function getCurrentLabelFromGetRequest(): ?ImageLabel {
        $currentLabel = null;
        if (isset($_GET[self::$LABEL_QUERYSTRING_KEY])) {
            $labelId = $_GET[self::$LABEL_QUERYSTRING_KEY];
            $currentLabel = $this->imageDao->getLabel($labelId);
        }
        return $currentLabel;
    }

    private function getCurrentLabelFromPostRequest(): ?ImageLabel {
        $currentLabel = null;
        if (isset($_POST["label_id"]) && $_POST["label_id"] != "") {
            $currentLabel = $this->imageDao->getLabel($_POST["label_id"]);
        }
        return $currentLabel;
    }

    private function addLabel(): void {
        $label = $this->imageDao->createLabel();
        $label->setName("Nieuw label");
        $this->redirectTo($this->getBackendBaseUrl() . "&label=" . $label->getId());
    }

    private function updateLabel(): void {
        try {
            $labelForm = new LabelForm($this->currentLabel);
            $labelForm->loadFields();
            $this->imageDao->updateLabel($this->currentLabel);
            $this->sendSuccessMessage("Label succesvol opgeslagen");
        } catch (FormException $e) {
            $this->sendErrorMessage("Label niet opgeslagen, verwerk de fouten");
        }
    }

    private function deleteLabels(): void {
        $labels = $this->imageDao->getAllLabels();
        foreach ($labels as $label) {
            if (isset($_POST["label_" . $label->getId() . "_delete"])) {
                $this->imageDao->deleteLabel($label);
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
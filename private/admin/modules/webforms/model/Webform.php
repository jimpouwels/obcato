<?php
require_once CMS_ROOT . "/modules/templates/model/Presentable.php";

class WebForm extends Presentable {

    public static int $SCOPE = 19;
    private string $_title;
    private array $_form_fields;
    private bool $_include_captcha;
    private ?string $_captcha_key;

    public function __construct() {
        parent::__construct(self::$SCOPE);
    }

    public static function constructFromRecord(array $record, array $form_fields): WebForm {
        $form = new WebForm();
        $form->setFormFields($form_fields);
        $form->initFromDb($record);
        return $form;
    }

    protected function initFromDb(array $row): void {
        $this->setTitle($row['title']);
        $this->setIncludeCaptcha($row['include_captcha'] == 1);
        $this->setCaptchaKey($row['captcha_key']);
        parent::initFromDb($row);
    }

    public function getTitle(): string {
        return $this->_title;
    }

    public function setTitle(string $title): void {
        $this->_title = $title;
    }

    public function getFormFields(): array {
        usort($this->_form_fields, function (WebFormItem $f1, WebFormItem $f2) {
            return $f1->getOrderNr() - $f2->getOrderNr();
        });
        return $this->_form_fields;
    }

    public function setFormFields(array $form_fields): void {
        $this->_form_fields = $form_fields;
    }

    public function deleteWebFormItem(int $form_item_id): void {
        $this->_form_fields = array_filter($this->_form_fields, function ($item) use ($form_item_id) {
            return $item->getId() !== $form_item_id;
        });
    }

    public function getIncludeCaptcha(): bool {
        return $this->_include_captcha;
    }

    public function setIncludeCaptcha(bool $include_captcha): void {
        $this->_include_captcha = $include_captcha;
    }

    public function getCaptchaKey(): ?string {
        return $this->_captcha_key;
    }

    public function setCaptchaKey(?string $captcha_key): void {
        $this->_captcha_key = $captcha_key;
    }

}
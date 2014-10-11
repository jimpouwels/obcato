<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/forms/form.php";

    class QueryForm extends Form {

        private $_query;

        public function loadFields() {
            $this->_query = $this->getMandatoryFieldValue('query', 'U heeft geen query ingevoerd');
            if ($this->hasErrors())
                throw new FormException();
        }

        public function getQuery() {
            return $this->_query;
        }
    }
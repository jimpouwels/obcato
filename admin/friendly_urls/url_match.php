<?php
    defined('_ACCESS') or die;

    class UrlMatch {

        private ?ElementHolder $_element_holder;
        private ?string $_url;
        public function __construct(?ElementHolder $element_holder, ?string $url) {
            $this->_element_holder = $element_holder;
            $this->_url = $url;
        }

        public function getElementHolder(): ?ElementHolder {
            return $this->_element_holder;
        }

        public function getUrl(): ?string {
            return $this->_url;
        }


    }

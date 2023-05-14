<?php

    defined('_ACCESS') or die;

    class HandlerProperty {

        private string $_name;
        private string $_type;
        private ?Visual $_editor = null;

        public function setName(string $name): void {
            $this->_name = $name;
        }

        public function getName(): string {
            return $this->_name;
        }

        public function setType(string $type): void {
            $this->_type = $type;
        }

        public function getType(): string {
            return $this->_type;
        }

        public function setEditor(?Visual $editor): void {
            $this->_editor = $editor;
        }

        public function getEditor(): ?Visual {
            return $this->_editor;
        }

        public function __construct(string $name, string $type, Visual $editor = null) {
            $this->_name = $name;
            $this->_type = $type;
            $this->_editor = $editor;
        }

    }

?>
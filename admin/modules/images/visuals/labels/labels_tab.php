<?php
    
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/visual.php";
    require_once CMS_ROOT . "modules/images/visuals/labels/labels_list.php";
    require_once CMS_ROOT . "modules/images/visuals/labels/label_editor.php";
    
    class LabelsTab extends Visual {
    
        private static $TEMPLATE = "images/labels/root.tpl";

        private $_current_label;
        private $_label_pre_handler;
        
        public function __construct($label_pre_handler) {
            parent::__construct();
            $this->_label_pre_handler = $label_pre_handler;
            $this->_current_label = $this->_label_pre_handler->getCurrentLabel();
        }
    
        public function renderVisual(): string {
            if (!is_null($this->_current_label)) {
                $this->getTemplateEngine()->assign("label_editor", $this->renderLabelEditor());
            }
            $this->getTemplateEngine()->assign("labels_list", $this->renderLabelsList());
            return $this->getTemplateEngine()->fetch("modules/" . self::$TEMPLATE);
        }
        
        private function renderLabelEditor() {
            $label_editor = new LabelEditor($this->_current_label);
            return $label_editor->render();
        }
        
        private function renderLabelsList() {
            $labels_list = new LabelsList();
            return $labels_list->render();
        }
    
    }
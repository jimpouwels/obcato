<?php
    
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/visual.php";
    require_once CMS_ROOT . "modules/images/visuals/labels/labels_list.php";
    require_once CMS_ROOT . "modules/images/visuals/labels/label_editor.php";
    
    class LabelsTab extends Visual {
    
        private static string $TEMPLATE = "images/labels/root.tpl";
        private ?ImageLabel $_current_label;
        private LabelRequestHandler $_label_request_handler;
        
        public function __construct(LabelRequestHandler $label_request_handler) {
            parent::__construct();
            $this->_label_request_handler = $label_request_handler;
            $this->_current_label = $this->_label_request_handler->getCurrentLabel();
        }
    
        public function render(): string {
            if (!is_null($this->_current_label)) {
                $this->getTemplateEngine()->assign("label_editor", $this->renderLabelEditor());
            }
            $this->getTemplateEngine()->assign("labels_list", $this->renderLabelsList());
            return $this->getTemplateEngine()->fetch("modules/" . self::$TEMPLATE);
        }
        
        private function renderLabelEditor(): string {
            $label_editor = new LabelEditor($this->_current_label);
            return $label_editor->render();
        }
        
        private function renderLabelsList(): string {
            $labels_list = new LabelsList();
            return $labels_list->render();
        }
    
    }
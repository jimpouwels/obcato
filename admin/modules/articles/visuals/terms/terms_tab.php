<?php
    
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "modules/articles/visuals/terms/terms_list.php";
    require_once CMS_ROOT . "modules/articles/visuals/terms/term_editor.php";
    
    class TermTab extends Visual {
    
        private static $TERM_MANAGER_TEMPLATE = "articles/terms/root.tpl";
        private static $TERM_QUERYSTRING_KEY = "term";
        private static $NEW_TERM_QUERYSTRING_KEY = "new_term";
    
        private $_current_term;
    
        public function __construct($current_term) {
            parent::__construct();
            $this->_current_term = $current_term;
        }
    
        public function renderVisual(): string {
            if ($this->isEditTermMode()) {
                $this->getTemplateEngine()->assign("term_editor", $this->renderTermEditor());
            }
            $this->getTemplateEngine()->assign("term_list", $this->renderTermsList());
            
            return $this->getTemplateEngine()->fetch("modules/" . self::$TERM_MANAGER_TEMPLATE);
        }
        
        public static function isEditTermMode() {
            return (isset($_GET[self::$TERM_QUERYSTRING_KEY]) && $_GET[self::$TERM_QUERYSTRING_KEY] != '') || 
                   (isset($_GET[self::$NEW_TERM_QUERYSTRING_KEY]) && $_GET[self::$NEW_TERM_QUERYSTRING_KEY] == 'true');
        }
        
        private function renderTermEditor() {
            $term_editor = new TermEditor($this->_current_term);
            return $term_editor->render();
        }
        
        private function renderTermsList() {
            $term_list = new TermsList();
            return $term_list->render();
        }
    }
    
?>
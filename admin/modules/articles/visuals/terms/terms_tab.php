<?php
    
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "modules/articles/visuals/terms/terms_list.php";
    require_once CMS_ROOT . "modules/articles/visuals/terms/term_editor.php";
    
    class TermTab extends Visual {
    
        private static string $TERM_QUERYSTRING_KEY = "term";
        private static string $NEW_TERM_QUERYSTRING_KEY = "new_term";
    
        private ?ArticleTerm $_current_term;
    
        public function __construct(?ArticleTerm $current_term) {
            parent::__construct();
            $this->_current_term = $current_term;
        }

        public function getTemplateFilename(): string {
            return "modules/articles/terms/root.tpl";
        }
    
        public function load(): void {
            if ($this->isEditTermMode()) {
                $this->assign("term_editor", $this->renderTermEditor());
            }
            $this->assign("term_list", $this->renderTermsList());
        }
        
        public static function isEditTermMode(): bool {
            return (isset($_GET[self::$TERM_QUERYSTRING_KEY]) && $_GET[self::$TERM_QUERYSTRING_KEY] != '') || 
                   (isset($_GET[self::$NEW_TERM_QUERYSTRING_KEY]) && $_GET[self::$NEW_TERM_QUERYSTRING_KEY] == 'true');
        }
        
        private function renderTermEditor(): string {
            $term_editor = new TermEditor($this->_current_term);
            return $term_editor->render();
        }
        
        private function renderTermsList(): string {
            $term_list = new TermsList();
            return $term_list->render();
        }
    }
    
?>
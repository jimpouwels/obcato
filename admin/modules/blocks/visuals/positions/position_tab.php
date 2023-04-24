<?php
    
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "modules/blocks/visuals/positions/position_editor.php";
    require_once CMS_ROOT . "modules/blocks/visuals/positions/position_list.php";
    
    class PositionTab extends Visual {
    
        private static string $POSITION_QUERYSTRING_KEY = "position";
        private static string $NEW_POSITION_QUERYSTRING_KEY = "new_position";
    
        private ?BlockPosition $_current_position = null;
    
        public function __construct(?BlockPosition $current_position) {
            parent::__construct();
            $this->_current_position = $current_position;
        }

        public function getTemplateFilename(): string {
            return "modules/blocks/positions/root.tpl";
        }
    
        public function load(): void {
            if ($this->isEditPositionMode()) {
                $this->assign("position_editor", $this->renderPositionEditor());
            }
            $this->assign("position_list", $this->renderPositionList());
        }
        
        public static function isEditPositionMode(): bool {
            return (isset($_GET[self::$POSITION_QUERYSTRING_KEY]) && $_GET[self::$POSITION_QUERYSTRING_KEY] != '');
        }
        
        private function renderPositionEditor(): string {
            $position_editor = new PositionEditor($this->_current_position);
            return $position_editor->render();
        }
        
        private function renderPositionList(): string {
            $position_list = new PositionList();
            return $position_list->render();
        }
    
    }
    
?>
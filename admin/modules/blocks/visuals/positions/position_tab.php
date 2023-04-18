<?php
    
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "modules/blocks/visuals/positions/position_editor.php";
    require_once CMS_ROOT . "modules/blocks/visuals/positions/position_list.php";
    
    class PositionTab extends Visual {
    
        private static string $TEMPLATE = "blocks/positions/root.tpl";
        private static string $POSITION_QUERYSTRING_KEY = "position";
        private static string $NEW_POSITION_QUERYSTRING_KEY = "new_position";
    
        private ?BlockPosition $_current_position = null;
    
        public function __construct(?BlockPosition $current_position) {
            parent::__construct();
            $this->_current_position = $current_position;
        }
    
        public function render(): string {
            if ($this->isEditPositionMode()) {
                $this->getTemplateEngine()->assign("position_editor", $this->renderPositionEditor());
            }
            $this->getTemplateEngine()->assign("position_list", $this->renderPositionList());
            
            return $this->getTemplateEngine()->fetch("modules/" . self::$TEMPLATE);
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
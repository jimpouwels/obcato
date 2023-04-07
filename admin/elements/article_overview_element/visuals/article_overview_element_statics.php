<?php
    
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/visual.php";

    class ArticleOverviewElementStatics extends Visual {
    
        private static $TEMPLATE = "elements/article_overview_element/article_overview_element_statics.tpl";
        
        private $_template_engine;
    
        public function __construct() {
            parent::__construct();
            $this->_template_engine = TemplateEngine::getInstance();
        }
        
        public function renderVisual(): string {
            return $this->_template_engine->fetch(self::$TEMPLATE);
        }
    
    }
    
?>
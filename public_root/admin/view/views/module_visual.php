<?php
    defined('_ACCESS') or die;
    
    abstract class ModuleVisual extends Visual {
        
        abstract function getActionButtons();
        
        abstract function getHeadIncludes();
        
        abstract function getRequestHandlers();
        
        abstract function onPreHandled();
        
        abstract function getTitle();
    
    }
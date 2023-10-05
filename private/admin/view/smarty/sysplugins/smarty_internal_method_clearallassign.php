<?php



/**
 * Smarty Method ClearAllAssign
 *
 * Smarty::clearAllAssign() method
 *
 * @package    Smarty
 * @subpackage PluginsInternal
 * @author     Uwe Tews
 */
class Smarty_Internal_Method_ClearAllAssign
{
    /**
     * Valid for all objects
     *
     * @var int
     */
    public $objMap = 7;

    /**
     * clear all the assigned template variables.
     *
     * @param \admin\view\smarty\sysplugins\Smarty_Internal_Data|\Smarty_Internal_Template|\Smarty $data
     *
     * @return \admin\view\smarty\sysplugins\Smarty_Internal_Data|\Smarty_Internal_Template|\Smarty
     *@api  Smarty::clearAllAssign()
     * @link https://www.smarty.net/docs/en/api.clear.all.assign.tpl
     *
     */
    public function clearAllAssign(Smarty_Internal_Data $data)
    {
        $data->tpl_vars = array();
        return $data;
    }
}

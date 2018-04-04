<?php
class Gtc_Action_ActionMeta extends Mi2_Adt_AssociativeList
{
    protected function getTableName()
    {
        return 'gtc_action_meta';
    }
    
    protected function getListIdColumnName()
    {
        return 'action_id';   
    }
    
    protected function getElementIdColumnName()
    {
        return 'meta_id';
    }
    
    protected function getKeyColumnName()
    {
        return 'meta_key';
    }
    
    protected function getValueColumnName()
    {
        return 'meta_value';
    }
}

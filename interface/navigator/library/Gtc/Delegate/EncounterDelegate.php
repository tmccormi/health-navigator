<?php
class Gtc_Delegate_EncounterDelegate implements Gtc_Action_ActionObserverIF
{
    public function getObserverId()
    {
        return 'encounter-delegate';    
    }   

    public function onStartProgress()
    {
        // When progress is started on an action
        // 1 - see if there's an encounter created, if so, load it.
        //    if not, create one and load it.
        // 2 - load the patient that the action belongs to.
    }
    
    public function onStopProgress()
    {
        
    }
}

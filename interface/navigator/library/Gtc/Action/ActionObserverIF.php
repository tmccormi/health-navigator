<?php
interface Gtc_Action_ActionObserverIF
{
    public function getObserverId();
    public function onStartProgress();
    public function onStopProgress();
}
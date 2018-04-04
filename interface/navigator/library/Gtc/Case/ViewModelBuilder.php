<?php
class Gtc_Case_ViewModelBuilder
{
    public static function buildViewModel( Gtc_Case $case = null, $mode = Gtc_Case_ViewModel::CREATE )
    {
        $actionTypeManager = new Gtc_Action_ActionTypeManager();
        $actionTypeOptions = $actionTypeManager->fetchActionTypeOptions();
        $ownerManager = new Gtc_Case_OwnerManager();
        $groups = $ownerManager->fetchGroupOptions();
        $groupOptions = $groups;
        foreach ( $groups as $key => $value ) {
            $group = $value;
            break;
        }
        $caseManager = new Gtc_Case_CaseManager();
        
        if ( $case == null ) {
            $case = new Gtc_Case( array(
                    'clientId' => $_SESSION['pid'],
                    'creatorId' => $_SESSION['authUserID'] ) );
        }
        
        $userOptions = $ownerManager->fetchUserOptions( $group );
        
        $viewModel = new Gtc_Case_ViewModel( array(
                'case' => $case,
                'actionTypeOptions' => $actionTypeOptions,
                'referenceOptions' => $caseManager->fetchReferenceTypeOptions(),
                'groupOptions' => $groupOptions,
                'userOptions' => $userOptions,
                'mode' => $mode ) );
        return $viewModel;
        
    }    
}

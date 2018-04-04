<?php
 // Copyright (C) 2010-2011 Aron Racho <aron@mi-squred.com>
 //
 // This program is free software; you can redistribute it and/or
 // modify it under the terms of the GNU General Public License
 // as published by the Free Software Foundation; either version 2
 // of the License, or (at your option) any later version.

class CaseController extends Mi2_Mvc_BaseController 
{   
    public function _action_create()
    {
        $viewModel = Gtc_Case_ViewModelBuilder::buildViewModel();
        $this->view->vm = $viewModel;
        $this->setViewScript( "case/form.php" );
    }
    
    public function _action_edit()
    {
        $caseId = $this->getRequest()->getParam( 'caseId' );
        $caseManager = new Gtc_Case_CaseManager();
        $case = $caseManager->findAndAssemble( $caseId );
        $viewModel = Gtc_Case_ViewModelBuilder::buildViewModel( $case, Gtc_Case_ViewModel::EDIT );
        $this->view->vm = $viewModel;
        $this->setViewScript( "case/form.php" );
    }
    
    public function _action_validate()
    {
        $caseManager = new Gtc_Case_CaseManager();
        $cases = $caseManager->createCasesFromRequest( $request );
        // validate each case
        foreach ( $cases as $case ) {
        
        }
    }
    
    public function _action_save()
    {
        $caseManager = new Gtc_Case_CaseManager();
        $case = $caseManager->createAndSaveCaseFromRequest( $this->getRequest() );        
    }
    
    // render user options given a group
    public function _action_useroptions()
    {
        $group = $this->getRequest()->getParam( 'group' );
        $ownerManager = new Gtc_Case_OwnerManager();
        $this->view->options = $ownerManager->fetchUserOptions( $group );
        $html = $this->view->render( 'case/_user_options.php' );
        echo $html;
        exit;
    }
    
    public function _action_view()
    {
        $caseId = $this->getRequest()->getParam( 'caseId' );
        $caseManager = new Gtc_Case_CaseManager();
        $case = $caseManager->findAndAssemble( $caseId );
        $viewModel = Gtc_Case_ViewModelBuilder::buildViewModel( $case, Gtc_Case_ViewModel::VIEW );
        $this->view->vm = $viewModel;
        $this->setViewScript( 'case/view.php' );
    }
    
    public function _action_current()
    {
        // Find the current patient, and their latest case
        $patientId = $_SESSION['pid'];
        $caseManager = new Gtc_Case_CaseManager();
        $cases = $caseManager->fetchByPatientId( $patientId );
        foreach ( $cases as $case ) {
            break;
        }
        $case = $caseManager->findAndAssemble( $case->getCaseId() );
        $viewModel = Gtc_Case_ViewModelBuilder::buildViewModel( $case, Gtc_Case_ViewModel::VIEW );
        $this->view->vm = $viewModel;
        $this->setViewScript( 'case/view.php' );
    }
    
    public function _action_listview()
    {
        $caseId = $this->getRequest()->getParam( 'id' );
        $caseManager = new Gtc_Case_CaseManager();
        $case = $caseManager->findAndAssemble( $caseId );
        $viewModel = Gtc_Case_ViewModelBuilder::buildViewModel( $case, Gtc_Case_ViewModel::VIEW );
        $this->view->vm = $viewModel;
        $html = $this->view->render( 'case/_view.php' );
        echo $html;
        exit;
    }
}


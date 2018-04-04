<?php
 // Copyright (C) 2013 Ken Chapple <ken@mi-squred.com>
 //
 // This program is free software; you can redistribute it and/or
 // modify it under the terms of the GNU General Public License
 // as published by the Free Software Foundation; either version 2
 // of the License, or (at your option) any later version.
 
class ActionController extends Mi2_Mvc_BaseController 
{   
    public function _action_create()
    {
        $this->setViewScript( "case/form.php" );
    }
    
    public function _action_save()
    {

    }

    public function _action_setstatus()
    {
        $actionId = $this->getRequest()->getParam( 'actionId' );
        $status = $this->getRequest()->getParam( 'status' );
        $actionManager = new Gtc_Action_ActionManager();
        $action = $actionManager->find( $actionId );
        if ( $status == Gtc_Action::START_PROGRESS ) {
            $action->startProgress(); 
        } else if ( $status == Gtc_Action::STOP_PROGRESS ) {
            $action->stopProgress();
        } else {
            $action->setMeta( 'action_status', $status );
        }
    }
    
    public function _action_setstate()
    {
        $actionId = $this->getRequest()->getParam( 'actionId' );
        $stateId = $this->getRequest()->getParam( 'stateId' );
        $actionManager = new Gtc_Action_ActionManager();
        $action = $actionManager->find( $actionId );
        $stateManager = new Gtc_State_StateManager();
        $state = $stateManager->find( $stateId );
        $action->setState( $state );
        $actionManager->save( $action );
    }
    
    public function _action_queue()
    {
        $html = $this->view->render( 'action/queue.php'  );
        echo $html;
        exit;
    }
    
    public function _action_current()
    {
        $actionId = $this->getRequest()->getParam( 'actionId' );
        $actionManager = new Gtc_Action_ActionManager();
        $caseManager = new Gtc_Case_CaseManager();
        $action = $actionManager->findAndAssemble( $actionId );
        $case = $caseManager->findAndAssemble( $action->getCaseId() );
        $action->setCase( $case );
        $this->view->action = $action;
        
        $inProgress = $actionManager->fetchInProgress( $_SESSION['authUser'] );
        $actionsInProgress = array();
        foreach ( $inProgress as $ip ) {
            $action = $actionManager->assemble( $ip );
            $case = $caseManager->findAndAssemble( $action->getCaseId() );
            $action->setCase( $case );
            $actionsInProgress[]= $action;
        }
        $this->view->actionsInProgress = $actionsInProgress;
        
        $html = $this->view->render( 'action/current.php'  );
        echo $html;
        exit;
    }
    
    public function _action_note_submit()
    {
        // this is an ajax call to submit a note
        require_once( $GLOBALS['srcdir']."/../interface/forms/gtc_note/FormNote.php" );
        $form = new FormNote( array( 'popup' => true ) );
        $form->setAction( $GLOBALS['web_root']."/interface/navigator/index.php?action=action!note_submit" );
        $params = $this->request->getParams();
        if ( $form->isValid( $params ) ) {
            if ( isset($params['mode']) ) {
                $form->_mode = $params['mode'];
            }
            if ( isset($params['id']) ) {
                $form->_id = $params['id'];
            }
            if ( isset($params['reminder_date']) && 
                    !empty($params['reminder_date'] ) ) {
                require_once( $GLOBALS['srcdir']."/dated_reminder_functions.php" );
        
                if ( $form->_mode == Mi2_Ui_AbstractForm::MODE_UPDATE ) {
                    $params['reminder_date'] = str_replace( ' 00:00:00', '', $params['reminder_date'] );
                }
                sendReminder(
                        $params['assigned_to'],
                        $form->_currentUser['id'],
                        $params['body'],
                        $params['reminder_date'],
                        $form->_pid, 1 );
            }
            
            $form->save( $params );

        } else {
            $form->populate( $params );
        }
        
        exit;
    }
    
    public function _action_note()
    {
        $actionId = $this->getRequest()->getParam( 'actionId' );
        $actionManager = new Gtc_Action_ActionManager();
        $action = $actionManager->find( $actionId );
        $caseId = $action->getCaseId();
        $caseManager = new Gtc_Case_CaseManager();
        $case = $caseManager->find( $caseId );
        $encounter = $action->getMeta( 'encounter' );
        if ( empty($encounter) ) {
            // no encounter created, so create one.
            $encounter = $actionManager->createEncounterForAction( $action, $case->getClientId() );
            $action->setMeta( 'encounter', $encounter );
        }
        
        require_once( $GLOBALS['srcdir']."/../interface/forms/gtc_note/FormNote.php" );
        $form = new FormNote( array( 'popup' => true ) );
        $form->setAction( $GLOBALS['web_root']."/interface/navigator/index.php?action=action!note_submit" );
        $this->view->form = $form;
        $this->setViewScript( "templates/form.php" );
    }
    
    public function _action_work()
    {
        $actionId = $this->getRequest()->getParam( 'actionId' );
        $actionManager = new Gtc_Action_ActionManager();
        $action = $actionManager->find( $actionId );
        $caseId = $action->getCaseId();
        $caseManager = new Gtc_Case_CaseManager();
        $case = $caseManager->find( $caseId );
        $sql = new Mi2_Db_Sql();
        $sql->create( array( 'P' => 'patient_data' ) );
        $sql->addFields( array( "P.pid", "P.pubpid", "P.DOB", "P.fname", "P.lname" ) );
        $sql->addSearchFilter( 
                new Mi2_Db_SearchFilter( $case->getClientId(), 'P', 'pid', Mi2_Db_SearchFilter::TYPE_STRICT ) );
        $sql->execute();
        $row = array();
        while ( $row = $sql->fetchNext() ) {
            break;
        }
        
        $srcdir = $GLOBALS['srcdir'];
        global $srcdir;
        include_once($GLOBALS['srcdir']."/pid.inc");
        include_once($GLOBALS['srcdir']."/encounter.inc");
        include_once($GLOBALS['srcdir']."/patient.inc");
        $_SESSION['pid'] = $row['pid'];
        setpid( $row['pid'] );
        // Set patient stuff on the view
        $this->view->pname = htmlspecialchars(($row['fname']) . " " . ($row['lname']),ENT_QUOTES);
        $this->view->pid = $row['pid'];
        $this->view->pubpid = $row['pubpid'];
        $this->view->dob = $row['DOB'];//htmlspecialchars(xl('DOB') . ": " . oeFormatShortDate(date(strtotime($row['DOB']))) . " " . xl('Age') . ": " . getPatientAge($row['DOB_YMD']), ENT_QUOTES);

        $encounter = $action->getMeta( 'encounter' );
        if ( empty($encounter) ) {
            // no encounter created, so create one.
            $encounter = $actionManager->createEncounterForAction( $action, $case->getClientId() );
            $action->setMeta( 'encounter', $encounter );
        }
        setencounter( $encounter );
        
        $sql = new Mi2_Db_Sql();
        $sql->create( array( 'E' => 'form_encounter' ) );
        $sql->addFields( array( 'E.date', 'E.encounter' ) );
        $sql->addSearchFilter( 
                new Mi2_Db_SearchFilter( $encounter, 'E', 'encounter', Mi2_Db_SearchFilter::TYPE_STRICT ) );
        $sql->execute();
        $row = array();
        while ( $row = $sql->fetchNext() ) {
            break;
        }
        // set encounter stuff on the view
        $this->view->edate = $row['date'];
        $this->view->eid = $row['encounter'];
        $this->view->actionId = $actionId;
        $this->view->url = "/interface/patient_file/encounter/encounter_top.php?set_encounter=".$row['encounter']."&set_patient=".$_SESSION['pid'];
        
        Gtc_Action_ActionManager::setActiveActionId( $actionId );
        $this->setViewScript( 'action/work.php'  );
    }
}

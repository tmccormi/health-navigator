<?php
class Bootstrap
{
    public function __construct()
    {
//        $this->initDatabase();
        $this->_initActiveUser(); 
    }
    

    
//     protected function initDatabase()
//     {
//         Zend_Db_Table_Abstract::setDefaultAdapter( $this->getDatabase() );
//     }
    
    protected function _initActiveUser()
    {
        $ownerManager = new Gtc_Case_OwnerManager();
        $activeUser = $ownerManager->findByUsername( $_SESSION['authUser'] );
        Zend_Registry::set( 'activeUser', $activeUser );
    }
    
//     protected function getDatabase()
//     {
//         global $sqlconf;
//         return Zend_Db::factory('Pdo_Mysql', array(
//                 'port' => $sqlconf["port"],
//                 'host' => $sqlconf["host"],
//                 'username' => $sqlconf["login"],
//                 'password' => $sqlconf["pass"],
//                 'dbname' => $sqlconf["dbase"]
//         ));
//     }
    
     
}

<?php

class Friendship_Confirm{
    protected $_dbConnection, $_dbInstance;

    public function __construct($usersID, $selectedID) {
        $this->_dbInstance = Database::getInstance();
        $this->_dbConnection = $this->_dbInstance->getDbConnection();

        if($usersID > $selectedID) { //Arranges ids in format smallestID + _ + largestID (Friends primary key format)
            $pk = $selectedID.'_'.$usersID;
            $temp = $usersID; //Makes usersID the smaller number

        }
        else{
            $pk=$usersID.'_'.$selectedID;

        }
        $friendCode = 3;
        $sqlQuery = 'UPDATE Friends SET friendCode=? WHERE user1User2=?';
        $statement = $this->_dbConnection->prepare($sqlQuery);
        $statement->bindParam(1, $friendCode); //Binds params; prevents SQL injection
        $statement->bindParam(2, $pk);
        $statement->execute();
    }
}
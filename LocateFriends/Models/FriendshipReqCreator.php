<?php

class FriendshipReqCreator {
    protected $_dbConnection, $_dbInstance;

    public function __construct($usersID, $selectedID) {
        $this->_dbInstance = Database::getInstance();
        $this->_dbConnection = $this->_dbInstance->getDbConnection();

        if($usersID > $selectedID) { //Arranges ids in format smallestID + _ + largestID (Friends primary key format)
            $pk = $selectedID.'_'.$usersID;
            $temp = $usersID; //Makes usersID the smaller number
            $usersID = $selectedID;
            $selectedID = $temp;
            $friendCode =2;
        }
        else{
            $pk=$usersID.'_'.$selectedID;
            $friendCode =1;
        }
        $sqlQuery = 'INSERT INTO Friends VALUES(?,?,?,?)';
        $statement = $this->_dbConnection->prepare($sqlQuery);
        $statement->bindParam(1, $pk); //Binds params; prevents SQL injection
        $statement->bindParam(2, $usersID);
        $statement->bindParam(3, $selectedID);
        $statement->bindParam(4, $friendCode);
        $statement->execute();
    }
}
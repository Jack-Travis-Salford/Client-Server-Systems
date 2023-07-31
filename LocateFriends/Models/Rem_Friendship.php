<?php

class Rem_Friendship
{
    protected $_dbConnection, $_dbInstance;

    public function __construct($usersID, $selectedID)
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbConnection = $this->_dbInstance->getDbConnection();

        if ($usersID > $selectedID) { //Arranges ids in format smallestID + _ + largestID (Friends primary key format)
            $pk = $selectedID . '_' . $usersID;
        } else {
            $pk = $usersID . '_' . $selectedID;
        }

        $sqlQuery = 'DELETE FROM Friends WHERE user1User2=?';
        $statement = $this->_dbConnection->prepare($sqlQuery);
        $statement->bindParam(1, $pk); //Binds params; prevents SQL injection
        $statement->execute();

    }
}
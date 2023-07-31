<?php
require_once 'Models/Database.php';
require_once 'Models/UserBasicData.php';
class User_Friends {
    protected $_dbConnection, $_dbInstance, $_total_matches;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbConnection = $this->_dbInstance->getDbConnection();
    }

    public function fetchSome($pageNo, $uID)
    {
        $offset = ($pageNo - 1) * 25; //Offset for fetched data
        //SQL to get friends of logged in user
        $sqlQuery = 'SELECT Users.userID, Users.username, Users.first_name, Users.surname FROM Users, Friends WHERE ((Users.userID = Friends.user1 and Friends.user2 ='.$uID.') or (Users.userID = Friends.user2 and Friends.user1= '.$uID.')) and friendCode = 3 LIMIT 25 OFFSET '.$offset;
        $statement = $this->_dbConnection->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) { //Gets the users
            $dataSet[] = new UserBasicData($row); //Takes row and get relevant variables
        }

        $sqlQuery = 'SELECT COUNT(user1User2) FROM Friends WHERE (user1='.$uID.' or user2='.$uID.') and friendCode = 3 '; //Counts total users than matched SQL

        $statement = $this->_dbConnection->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement
        $row = $statement->fetch();

        $this->_total_matches = $row["COUNT(user1User2)"]; //Saves count to Total Matches

        $this->_dbConnection = null;
        return $dataSet;
    }

    public function getTotalMatches()
    {
        return $this->_total_matches;
    }

}
<?php
require_once 'Models/Database.php';
require_once 'Models/UserBasicData.php';

class Index_Users {

    protected $_dbConnection, $_dbInstance, $_total_matches;

    public function __construct() {
        $this->_dbInstance = Database::getInstance();
        $this->_dbConnection = $this->_dbInstance->getDbConnection();
    }

    public function fetchSome($pageNo){
        $offset = ($pageNo-1)*25; //Offset for fetched data
        $sqlQuery = 'SELECT userID, username, first_name, surname FROM Users LIMIT 25 OFFSET '.$offset;

        $statement = $this->_dbConnection->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) { //Gets the users
            $dataSet[] = new UserBasicData($row); //Takes row and get relevant variables
        }

        $sqlQuery = 'SELECT COUNT(userID) FROM Users '; //Counts total users than matched SQL
        $statement = $this->_dbConnection->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement
        $row = $statement->fetch();
        $this->_total_matches = $row["COUNT(userID)"]; //Saves count to Total Matches

        $this->_dbConnection = null;
        return $dataSet;
    }

    public function getTotalMatches() {
        return $this->_total_matches;
    }
}
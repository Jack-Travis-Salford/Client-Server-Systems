<?php
require_once 'Models/Database.php';
require_once 'Models/UserBasicData.php';

class Basic_Search
{

    protected $_dbConnection, $_dbInstance, $_total_matches, $statement;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbConnection = $this->_dbInstance->getDbConnection();
    }

    public function executeStatement($pageNo, $search, $limit)
    {
        $offset = ($pageNo - 1) * 25; //Offset for fetched data


        $searchTerm = $search; //Gets what user searched for
        $searchTerm = trim($searchTerm); //Removes leading and trailing whitespace
        if (strpos($searchTerm, ' ') !== false){ //If string has spaces, then user must be searching for a name

            $sqlQuery = 'SELECT DISTINCT userID, username, first_name, surname FROM Users WHERE (select concat(first_name," ",surname)) like ? or surname like ? LIMIT '.$limit.' OFFSET ' . $offset;
            $search = $searchTerm."%"; //Match to user input (which contains a space) to (first_name + surname + 0+ more characters) or to surname which contains a space + 0 or more characters
            $this->statement = $this->_dbConnection->prepare($sqlQuery); // prepare a PDO statement
            $this->statement->bindParam(1, $search);
            $this->statement->bindParam(2, $search);
        }
        else { //User could be searching for anything

            $sqlQuery = 'SELECT DISTINCT userID, username, first_name, surname FROM Users WHERE userID LIKE ? or username LIKE ? or first_name LIKE ? or surname LIKE ? LIMIT '.$limit.' OFFSET ' . $offset;
            $this->statement = $this->_dbConnection->prepare($sqlQuery); // prepare a PDO statement
            $search = $searchTerm."%"; //Match if equal to user input + 0 or more other characters.
            $this->statement->bindParam(1, $search);
            $this->statement->bindParam(2, $search);
            $this->statement->bindParam(3, $search);
            $this->statement->bindParam(4, $search);
        }
        $this->statement->execute(); // execute the PDO statement


    }

    public function getDataSet() //Gets data set for php to use
    {
        $dataSet = [];
        while ($row = $this->statement->fetch()) { //Gets the users
            $dataSet[] = new UserBasicData($row); //Takes row and get relevant variables
        }

        $this->_dbConnection = null;
        return $dataSet;
    }

    public function getDataAsJson() { //Returns all data in json format
        $rows = $this->statement->fetchAll();
        $rows = json_encode($rows);
        echo $rows;
    }

    public function calculateTotalMatches($search)
    {
        $searchTerm = $search; //Gets what user searched for
        $searchTerm = trim($searchTerm); //Removes leading and trailing whitespace
        $this->_dbConnection = $this->_dbInstance->getDbConnection();
        if (strpos($searchTerm, ' ') !== false){ //If string has spaces, then user must be searching for a name

            $sqlQuery = 'SELECT DISTINCT COUNT(userID) FROM Users WHERE (select concat(first_name," ",surname)) like ? or surname like ?';
            $search = $searchTerm."%"; //Match to user input (which contains a space) to (first_name + surname + 0+ more characters) or to surname which contains a space + 0 or more characters
            $statement = $this->_dbConnection->prepare($sqlQuery); // prepare a PDO statement
            $statement->bindParam(1, $search);
            $statement->bindParam(2, $search);
        }
        else { //User could be searching for anything

            $sqlQuery = 'SELECT DISTINCT COUNT(userID) FROM Users WHERE userID LIKE ? or username LIKE ? or first_name LIKE ? or surname LIKE ?';
            $statement = $this->_dbConnection->prepare($sqlQuery); // prepare a PDO statement
            $search = $searchTerm."%"; //Match if equal to user input + 0 or more other characters.
            $statement->bindParam(1, $search);
            $statement->bindParam(2, $search);
            $statement->bindParam(3, $search);
            $statement->bindParam(4, $search);
        }
          $statement->execute(); // execute the PDO statement


        $row = $statement->fetch();
        $this->_total_matches = $row["COUNT(userID)"]; //Saves count to Total Matches



    }
    public function  getTotalMatches() {
        return $this->_total_matches;
    }



}
<?php
require_once 'Models/Database.php';
require_once 'Models/UserBasicData.php';

class Adv_Search {

    protected $_dbConnection, $_dbInstance, $_total_matches;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbConnection = $this->_dbInstance->getDbConnection();
    }

    public function fetchSome($pageNo)
    {
        $offset = ($pageNo - 1) * 25; //Offset for fetched data

        $sqlQuery = 'SELECT DISTINCT userID, username, first_name, surname FROM Users WHERE 1=1'; //Beginning of query, where 1=1 present in case where user submits blank search

        $sqlRestrictions = ""; //The WHERE clause for SQL
        $useUserID  = false; //If true, user refined search by user ID
        $userID = ""; //value entered for userID
        $useUsername  = false; //If true, user refined search by username
        $username = ""; //value entered for username
        $useFirstName  = false; //If true, user refined search by first name
        $firstName = ""; //value entered for first name
        $useSurname  = false; //If true, user refined search by surname
        $surname = ""; //value entered for surname
        $useCountry  = false; //If true, user refined search by country
        $country = ""; //value entered for country
        $useEmail  = false; //If true, user refined search by email
        $email = ""; //value entered for email

        if($_POST["userID"] != "") { //If user refined search by user ID
            if($_POST["userIDChoice"] == "like"){ //If user selected 'LIKE userID'
                $sqlRestrictions .= " AND userID LIKE ?"; //Add to sql
                $userID = "%".$_POST["userID"]."%"; //Set value of var for later
            }
            else {
                $sqlRestrictions .= " AND userID = ?"; //Add = version to sql
                $userID = $_POST["userID"]; //Set value of var for later
            }
            $useUserID = true; //Set boolean var to true
        }
        if($_POST["username"] != "") { //If user refined search by username
            if($_POST["usernameIDChoice"] == "like"){ //If user selected 'LIKE username'
                $sqlRestrictions .= " AND username LIKE ?"; //Add to sql
                $username = "%".$_POST["username"]."%"; //Set value of var for later
            }
            else {
                $sqlRestrictions .= " AND username = ?"; //Add = version to sql
                $username = $_POST["username"]; //Set value of var for later
            }
            $useUsername = true; //Set boolean var to true
        }
        if($_POST["first_name"] != "") { //If user refined search by first name
            if($_POST["first_nameIDChoice"] == "like"){ //If user selected 'LIKE first_name'
                $sqlRestrictions .= " AND first_name LIKE ?"; //Add to sql
                $firstName = "%".$_POST["first_name"]."%"; //Set value of var for later
            }
            else {
                $sqlRestrictions .= " AND first_name = ?"; //Add = version to sql
                $firstName = $_POST["first_name"]; //Set value of var for later
            }
            $useFirstName = true; //Set boolean var to true
        }
        if($_POST["surname"] != "") { //If user refined search by surname
            if($_POST["surnameIDChoice"] == "like"){ //If user selected 'LIKE surname'
                $sqlRestrictions .= " AND surname LIKE ?"; //Add to sql
                $surname = "%".$_POST["surname"]."%"; //Set value of var for later
            }
            else {
                $sqlRestrictions .= " AND surname = ?"; //Add = version to sql
                $surname = $_POST["surname"]; //Set value of var for later
            }
            $useSurname = true; //Set boolean var to true
        }
        if($_POST["country"] != "") { //If user refined search by country
            if($_POST["countryIDChoice"] == "like"){ //If user selected 'LIKE country'
                $sqlRestrictions .= " AND country LIKE ?"; //Add to sql
                $country = "%".$_POST["country"]."%"; //Set value of var for later
            }
            else {
                $sqlRestrictions .= " AND country = ?"; //Add = version to sql
                $country = $_POST["country"]; //Set value of var for later
            }
            $useCountry = true; //Set boolean var to true
        }
        if($_POST["email"] != "") { //If user refined search by country
            if($_POST["emailIDChoice"] == "like"){ //If user selected 'LIKE country'
                $sqlRestrictions .= " AND email LIKE ?"; //Add to sql
                $email = "%".$_POST["email"]."%"; //Set value of var for later
            }
            else {
                $sqlRestrictions .= " AND email = ?"; //Add = version to sql
                $email = $_POST["email"]; //Set value of var for later
            }
            $useEmail = true; //Set boolean var to true
        }
        if ($_POST['sortBy'] == "userID") { //if user selected sort by userID
            $sqlRestrictions .= " ORDER BY userID";
        }
        elseif ($_POST['sortBy'] == "username") { //if user selected sort by username
            $sqlRestrictions .= " ORDER BY username";
        }
        elseif ($_POST['sortBy'] == "firstName") { //if user selected sort by first name
            $sqlRestrictions .= " ORDER BY first_name";
        }
        elseif ($_POST['sortBy'] == "surname") { //if user selected sort by surname
            $sqlRestrictions .= " ORDER BY surname";
        }
        if ($_POST['order'] == "Asc"){ //if user selected sort by ascending
            $sqlRestrictions .= " ASC";
        }
        elseif ($_POST['order'] == "Desc") { //if user selected sort by descending
            $sqlRestrictions .= " DESC";
        }

        $sqlQuery .= $sqlRestrictions." LIMIT 25 OFFSET ".$offset; //Assembles sql statement

        $statement = $this->_dbConnection->prepare($sqlQuery); // prepare a PDO statement
        $x=1; //Counter for what parameter is being binded
        if($useUserID == true) { //If user filtered by said var, bind value
            $statement->bindParam($x, $userID);
            $x++;
        }
        if($useUsername == true) {
            $statement->bindParam($x, $username);
            $x++;
        }
        if($useFirstName == true) {
            $statement->bindParam($x, $firstName);
            $x++;
        }
        if($useSurname == true) {
            $statement->bindParam($x, $surname);
            $x++;
        }
        if($useCountry == true) {
            $statement->bindParam($x,$country);
            $x++;
        }
        if($useEmail == true) {
            $statement->bindParam($x, $email);
        }

        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) { //Gets the users
            $dataSet[] = new UserBasicData($row); //Takes row and get relevant variables
        }

        $sqlQuery = 'SELECT DISTINCT COUNT(userID) FROM Users WHERE 1=1'.$sqlRestrictions; //Counts total users than matched SQL
        $statement = $this->_dbConnection->prepare($sqlQuery); // prepare a PDO statement

        $x=1; //Counter for what parameter is being binded
        if($useUserID == true) { //If user filtered by said var, bind value
            $statement->bindParam($x, $userID);
            $x++;
        }
        if($useUsername == true) {
            $statement->bindParam($x, $username);
            $x++;
        }
        if($useFirstName == true) {
            $statement->bindParam($x, $firstName);
            $x++;
        }
        if($useSurname == true) {
            $statement->bindParam($x, $surname);
            $x++;
        }
        if($useCountry == true) {
            $statement->bindParam($x,$country);
            $x++;
        }
        if($useEmail == true) {
            $statement->bindParam($x, $email);
        }
        $statement->execute(); // execute the PDO statement
        $row = $statement->fetch();
        $this->_total_matches = $row["COUNT(userID)"]; //Saves count to Total Matches

        $this->_dbConnection = null;
        return $dataSet;
    }

    public function getTotalMatches()
    {
        return $this->_total_matches;
    }
}
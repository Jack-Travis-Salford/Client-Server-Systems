<?php
require_once 'Models/Database.php';
require_once  'Models/UserAdvData.php';

class Selected_User {

    protected $_dbConnection, $_dbInstance;

    public function __construct() {
        $this->_dbInstance = Database::getInstance();
        $this->_dbConnection = $this->_dbInstance->getDbConnection();
    }

    public function fetch($uID){
        $sqlQuery = 'SELECT username, first_name, surname, dob, gender, country FROM Users WHERE userID=?'; //Gets all necessary info about user

        $statement = $this->_dbConnection->prepare($sqlQuery); // prepare a PDO statement

        $statement->bindParam(1, $uID);
        $statement->execute(); // execute the PDO statement

        $row = $statement->fetch();
        $this->_dbConnection = null;
        if($row != false) { //If selected user exists
            $userInfo = new UserAdvData($row); //Use UserAdvData to separate row into variables
            return  $userInfo; //Return the UserAdvData
        }
        else {
            return false; //If user doesn't exist, return false
        }
    }

    public function fetchFriendship($usersID, $selectedID) {
        $isUser1 = false; //is true if logged in user has smaller ID value
        if($usersID > $selectedID) { //Arranges ids in format smallestID + _ + largestID (Friends primary key format)
            $pk = $selectedID.'_'.$usersID;
        }
        else{
            $pk=$usersID.'_'.$selectedID;
            $isUser1 = true;
        }
        $this->_dbInstance = Database::getInstance();
        $this->_dbConnection = $this->_dbInstance->getDbConnection();


        $sqlQuery = 'SELECT friendCode FROM Friends WHERE user1User2="'.$pk.'"'; //Gets all necessary info about user. Friend code is relationship in relation to user1


        $statement = $this->_dbConnection->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $row = $statement->fetch();
        $this->_dbConnection = null;
        if($row != false) { //If selected user exists
            $friendshipCode = $row['friendCode']; //Use UserAdvData to separate row into variables
            if($friendshipCode==1 and $isUser1 == false){ //If logged in user has higher ID value, swap friendship value
                $friendshipCode=2;
            }
            elseif ($friendshipCode==2 and $isUser1 == false) {
                $friendshipCode=1;
            }
            return  $friendshipCode; //Return the UserAdvData
        }
        else {
            return false; //If user doesn't exist, return false
        }
    }


}
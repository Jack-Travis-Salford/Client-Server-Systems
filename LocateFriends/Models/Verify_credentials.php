<?php
require_once 'Database.php';

class Verify_credentials {

    protected $_dbConnection, $_dbInstance, $_username, $_pass, $_uID;

    public function __construct($username, $pass) {
        $this->_dbInstance = Database::getInstance();
        $this->_dbConnection = $this->_dbInstance->getdbConnection();
        $this->_username = $username; //Saves value of username and password to class
        $this->_pass = $pass;
    }


    public function verify() { //Function that validates users login details
        //$sqlQuery = "SELECT username FROM Users WHERE username=? AND password=?";
        $sqlQuery = "SELECT userID FROM Users WHERE username=? AND password=?";
        $statement = $this->_dbConnection->prepare($sqlQuery);

        $statement->bindParam(1, $this->_username); //Binds params; prevents SQL injection
        $statement->bindParam(2, $this->_pass);
        $statement->execute();
        $row = $statement->fetch(); //username is unique: At most 1 result will show
        $this->_dbConnection=null;
        if($row==false){ //If a users details were wrong, return false. Otherwise, user details were correct, return true
            return false;
        }
        else{
            $this->_uID = $row['userID']; //Gets userID for SESSION variable
            return true;
        }

    }

    /**
     * @return mixed
     */
    public function getUID()
    {
        return $this->_uID;
    }
}
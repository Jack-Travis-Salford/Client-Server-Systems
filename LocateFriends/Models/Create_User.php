<?php
require_once('Models/Database.php');
class Create_User {
    protected $_dbConnection, $_dbInstance;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbConnection = $this->_dbInstance->getDbConnection();
    }
    public function isUsernameTaken($username) { //Checks to see if chosen username is already take. Returns a boolean value
        $sqlQuery="SELECT userID FROM Users WHERE username =?";
        $statement = $this->_dbConnection->prepare($sqlQuery);
        $statement->bindParam(1, $username);
        $statement->execute();
        $row=$statement->fetch();
        if($row == false){ //If nothing was returned (username doesn't yet exist), return true
            return false;
        }
        else{ //Else, return false
            return true;
        }
    }
    public function create() { //Creates new user
        $strTime = strtotime($_POST['birthday']); //Saves birthday as string
        $dob = date("d/m/Y",$strTime); //Changes format of birthday
        $pass_enc = hash("sha256", $_POST['password']); //Encrypts password using sha256 for database
        $_POST['password'] = null; //Overwrites super global value of the unencrypted password. Only the encrypted version remains.
        $_POST['password2'] = null; //Overwrites super global value of the unencrypted password. Only the encrypted version remains.

        $sqlQuery = "INSERT INTO Users (username, first_name, surname, password, dob, gender, email, country) VALUES (?,?,?,?,?,?,?,?)";
        $statement = $this->_dbConnection->prepare($sqlQuery);
        $statement->bindParam(1, $_POST['new_username']); //Binds parameters for above sql
        $statement->bindParam(2, $_POST['first_name']);
        $statement->bindParam(3, $_POST['surname']);
        $statement->bindParam(4, $pass_enc);
        $statement->bindParam(5, $dob);
        $statement->bindParam(6, $_POST['gender']);
        $statement->bindParam(7, $_POST['email']);
        $statement->bindParam(8, $_POST['country']);
        $statement->execute();
    }

    public function deleteLater($usersID, $selectedID) {
        if($usersID > $selectedID) { //Arranges ids in format smallestID + _ + largestID (Friends primary key format)
            $pk = $selectedID.'_'.$usersID;
            $temp = $usersID; //Makes usersID the smaller number
            $usersID = $selectedID;
            $selectedID = $temp;
            $friendCode = 2;
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
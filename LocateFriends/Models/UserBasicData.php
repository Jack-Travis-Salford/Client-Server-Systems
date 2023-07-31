<?php
//Holds data that all users can see (basic info about all users, nothing specific)
class UserBasicData{
    protected $_id, $_username, $_first_name, $_last_name; //

    public function __construct($dbrow) { //Passed dbrow. Split into relevant variables
        $this->_id = $dbrow["userID"];
        $this->_first_name = $dbrow["first_name"];
        $this->_last_name = $dbrow["surname"];
        $this->_username = $dbrow["username"];
    }

    /**
     * @return string First Name
     */
    public function getFirstName()
    {
        return $this->_first_name;
    }

    /**
     * @return Integer ID
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @return String Last Name
     */
    public function getLastName()
    {
        return $this->_last_name;
    }

    /**
     * @return String username
     */
    public function getUsername()
    {
        return $this->_username;
    }

}
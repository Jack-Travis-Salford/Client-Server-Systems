<?php
class UserAdvData {
    protected $_username, $_first_name, $_surname, $_dob, $_gender, $_country;

    public function __construct($row) { //Passed row from database, spilts row into variables and creates accessors
        $this->_username = $row['username'];
        $this->_first_name = $row['first_name'];
        $this->_surname = $row['surname'];
        $this->_dob = $row['dob'];
        $this->_gender = $row['gender'];
        $this->_country = $row['country'];
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->_username;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->_first_name;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->_country;
    }

    /**
     * @return mixed
     */
    public function getDob()
    {
        return $this->_dob;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->_gender;
    }

    /**
     * @return mixed
     */
    public function getSurname()
    {
        return $this->_surname;
    }




}
<?php
require_once 'Models/Database.php';

class LocatorUpdater{

    protected $_dbConnection, $_dbInstance;
    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbConnection = $this->_dbInstance->getDbConnection();
    }

    public function update($uID, $uLong, $uLat) {

        $sqlQuery = 'UPDATE Users SET longitude=?, latitude=? WHERE userID=?';
        $statement = $this->_dbConnection->prepare($sqlQuery);
        $statement->bindParam(1, $uLong); //Binds params; prevents SQL injection
        $statement->bindParam(2, $uLat);
        $statement->bindParam(3, $uID);
        $statement->execute();
    }


}
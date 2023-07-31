<?php
require_once('Database.php');


class Map_data {
    protected  $_dbInstance, $_dbConnection;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbConnection = $this->_dbInstance->getDbConnection();
    }

    public function getFriendsLocation($uID){ //Gets friends of requested user

//Get location data of all confirmed friends of the user, whos location data is set. Only retrieves 100;
        $sqlQuery ='SELECT DISTINCT Users.userID, Users.longitude, Users.latitude, Users.first_name, Users.surname, Users.username FROM Users, Friends WHERE ((Users.userID = Friends.user1 and Friends.user2 =?) or (Users.userID = Friends.user2 and Friends.user1= ?)) and friendCode = 3 and Users.longitude IS NOT NULL and Users.latitude IS NOT NULL ORDER BY Users.userID LIMIT 100 ';

        $statement = $this->_dbConnection->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindParam(1, $uID);
        $statement->bindParam(2, $uID);



        $statement->execute(); // execute the PDO statement


        $rows = $statement->fetchAll();
        $rows = json_encode($rows);

        echo $rows;
    }
}








<?php
session_start();
require_once ('Models/Map_data.php');
if (isset($_SESSION['uID'])){
    $uID = $_SESSION['uID']; //Gets users ID from session

    $friendLoc = new Map_data(); //Creates new Map class
    $data = $friendLoc->getFriendsLocation($uID); //Calls function to get data
    return $data; //Returns data to JS.
}
return null;
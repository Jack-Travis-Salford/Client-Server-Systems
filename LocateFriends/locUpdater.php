<?php
session_start();

if(isset($_SESSION['uLong']) && isset($_SESSION['uLat'])){ //If coordinate session vars are already set:
    if($_SESSION['uLong'] != $_REQUEST['uLong'] || $_SESSION['uLat'] != $_REQUEST['uLat']) { //If any of the users coordinates update
        updateRecord();
    }
    else{ //If users coordinates are same as before
        return false;
    }
} else{ //If session vars are not currently set, make naive assumption co-ordinates are different to what's currently in database (Insert is faster than Select, so opt to insert rather than select, followed by insert if co-ordinates are different)
    updateRecord();
}

function updateRecord() {
    $_SESSION['uLong'] = $_REQUEST['uLong']; //Save new coordinates to session
    $_SESSION['uLat'] = $_REQUEST['uLat'];
    require_once ("Models/LocatorUpdater.php");
    $reqUpdate = new LocatorUpdater();
    $reqUpdate->update($_SESSION['uID'], $_SESSION['uLong'], $_SESSION['uLat']); //Sends data to Model to update db record


}
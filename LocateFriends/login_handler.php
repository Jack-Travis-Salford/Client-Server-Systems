<?php //Handles POST if user attempts to login via sidebar, reusable code
require_once('Models/Verify_credentials.php'); //required if user has attempted to log in ($_POST)
if(isset($_POST['username']) and !isset($_SESSION['username'])) { //If a post request relating to logging in has been sent to this page, and the user isn't currently logged in
    if(isset($_POST['not_a_robot'])) {
        $pass_enc = hash("sha256", $_POST['pass']); //Encrypts password using sha256 for database

        $_POST['pass'] = null; //Overwrites super global value of the unencrypted password. Only the encrypted version remains.

        $verify = new Verify_credentials($_POST['username'], $pass_enc); //Call constructor of Verify_credentials, sending username and encrypted password
        $pass_enc = null; //Destroys pass_enc, as its no longer required
        $view->loginSuccessful = $verify->verify(); //Calls method that will verify login details. Returns either true or false
        if ($view->loginSuccessful == true) {
            $_SESSION['username'] = htmlentities($_POST['username']); //Create a session var to remember them
            $_SESSION['uID'] = $verify->getUID(); //Sets userID
            if (isset($_POST['remember'])) { //If user checked the 'Remember me' box
                if ($_POST['remember'] == "on") {
                    setcookie('username', htmlentities($_POST['username'])); //Set cookie to remember user
                    setcookie('uID', $verify->getUID); //Set cookie to remember userID
                }
            }
            header("location: ".$view->currentPage); //Select itself and reload page to set cookie value
            exit();
        }
    }
}
if(isset($_COOKIE['username']) AND !isset($_SESSION['username'])) { //For returning users who selected 'Remember Me'; Logs them back in (sets value of username in SESSION)
    $_SESSION['username'] = $_COOKIE['username'];
    $_SESSION['uID'] = $_COOKIE['uID'];
}


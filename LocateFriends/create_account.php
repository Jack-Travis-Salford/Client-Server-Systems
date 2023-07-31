<?php
$view = new stdClass();
$view->pageTitle = 'Create Account';
$view->currentPage = 'create_account.php'; //Holds current page. Can be used by forms that POST to a different page, but want to return to current page afterward.
session_start(); //Starts the session

require_once ('page_nav.php'); //Gets page navigation buttons
require_once('login_handler.php'); //Controls logging the user in, if user attempted to

if(isset($_POST['birthday'])) { //If there was a POST
    if(htmlentities($_POST['password']) == htmlentities($_POST['password2'])) { //If the passwords match
        $view->passMatch = true;
        require_once('Models/Create_User.php');
        $create_user = new Create_User();
        $view->isUsernameTaken = $create_user->isUsernameTaken($_POST['new_username']);
        if($view->isUsernameTaken == false){ //If username isn't taken
            $create_user->create();

        }
    }
    else {
        $view->passMatch = false; //Flag issue for error message
    }

}



require_once('Views/create_account.phtml');
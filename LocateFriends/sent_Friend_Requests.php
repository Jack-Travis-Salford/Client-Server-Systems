<?php
$view = new stdClass();
$view->pageTitle = 'Sent Friend Requests';
$view->currentPage = 'sent_friend_Requests.php'; //Holds current page. Can be used by forms that POST to a different page, but want to return to current page afterward.
session_start(); //Starts the session

require_once ('page_nav.php'); //Gets page navigation buttons
require_once('login_handler.php'); //Controls logging the user in, if user attempted to
if(isset($_SESSION['uID'])) {
    require_once('Models/Sent_Friend_Requests.php');
    $user_Friends = new Sent_Friend_Requests(); //Gets users for front page
    $view->userDataSet = $user_Friends->fetchSome($view->currentPageNo, $_SESSION['uID']); //Fetches users based off given offset (depending on current page
    $view->totalUsers = $user_Friends->getTotalMatches(); //Gets how many users matched sql statement

    require_once("Models/Calculate_pages.php");
    $view->Calculate_pages = new Calculate_pages($view->totalUsers, $view->currentPageNo);
}




require_once('Views/sent_Friend_Requests.phtml');
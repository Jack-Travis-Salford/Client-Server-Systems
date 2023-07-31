<?php
$view = new stdClass();
$view->pageTitle = 'Advanced Search';
$view->currentPage = 'advSearch.php'; //Holds current page. Can be used by forms that POST to a different page, but want to return to current page afterward.
session_start(); //Starts the session

require_once ('page_nav.php'); //Gets page navigation buttons


require_once('login_handler.php'); //Controls logging the user in, if user attempted to
if(isset($_SESSION['uID'])){
    if(isset($_POST["order"])) {
        require_once('Models/Adv_Search.php');
        $search = new Adv_Search(); //Gets users for front page
        $view->userDataSet = $search->fetchSome($view->currentPageNo);
        $view->totalUsers = $search->getTotalMatches(); //Gets how many users matched sql statement

        require_once("Models/Calculate_pages.php");
        $view->Calculate_pages = new Calculate_pages($view->totalUsers, $view->currentPageNo);
    }
    require_once('Views/advSearch.phtml');
}
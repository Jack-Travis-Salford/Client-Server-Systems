<?php
$view = new stdClass();
$view->pageTitle = 'Search';
$view->currentPage = 'search.php'; //Holds current page. Can be used by forms that POST to a different page, but want to return to current page afterward.
session_start(); //Starts the session

require_once ('page_nav.php'); //Gets page navigation buttons


require_once('login_handler.php'); //Controls logging the user in, if user attempted to

require_once('Models/Basic_Search.php');


$search = new Basic_Search(); //Gets users for front page
$search->executeStatement($view->currentPageNo, $_POST['searchCriteria'], 25); //SQL statement created and executed
$view->userDataSet = $search->getDataSet(); //Gets the data as objects

$search->calculateTotalMatches($_POST['searchCriteria']); //SQL statement to get total matches
$view->totalUsers = $search->getTotalMatches(); //Gets how many users matched sql statement
require_once("Models/Calculate_pages.php");
$view->Calculate_pages = new Calculate_pages($view->totalUsers, $view->currentPageNo);

require_once('Views/search.phtml');
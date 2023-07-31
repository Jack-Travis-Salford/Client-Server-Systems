<?php
$view = new stdClass();
$view->pageTitle = 'Homepage';
$view->currentPage = 'index.php'; //Holds current page. Can be used by forms that POST to a different page, but want to return to current page afterward.
session_start(); //Starts the session

require_once ('page_nav.php'); //Gets page navigation buttons


require_once('login_handler.php'); //Controls logging the user in, if user attempted to

require_once('Models/Index_Users.php');
$index_users = new Index_Users(); //Gets users for front page
$view->userDataSet = $index_users->fetchSome($view->currentPageNo); //Fetches users based off given offset (depending on current page
$view->totalUsers = $index_users->getTotalMatches(); //Gets how many users matched sql statement
require_once("Models/Calculate_pages.php");
$view->Calculate_pages = new Calculate_pages($view->totalUsers, $view->currentPageNo);

require_once('Views/index.phtml');


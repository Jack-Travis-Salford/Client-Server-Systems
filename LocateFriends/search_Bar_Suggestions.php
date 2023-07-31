<?php
//For javascript call
require_once("Models/Basic_Search.php");

$search = new Basic_Search(); //Reusing Basic_Search - Modified to work for this
$search->executeStatement(1, $_REQUEST["searchTerm"],10); //SQL statement - Get first 25 matches. PageNo = 1 (get first results)

echo $search->getDataAsJson();


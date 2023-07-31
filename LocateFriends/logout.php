<?php
session_start();
setcookie('username','',1); //Deletes cookie (removes value of cookie and sets it to expire after 1 second
setcookie('uID','',1);
unset($_SESSION["username"]); //Removes the value of username from session (logs user out)
unset($_SESSION["uID"]); //Removes the value of username from session (logs user out)
header("location: index.php");
exit();

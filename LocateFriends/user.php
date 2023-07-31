<?php
$view = new stdClass();
if(isset($_GET['selectedID'])) {
    $view->pageTitle = 'User'.$_GET['selectedID'];
    $view->currentPage = 'user.php?selectedID='.$_GET['selectedID']; //Holds current page. Can be used by forms that POST to a different page, but want to return to current page afterward.
}
elseif(isset($_POST['selectedID'])) {
    $view->pageTitle = 'User'.$_POST['selectedID'];
    $view->currentPage = 'user.php?selectedID='.$_POST['selectedID']; //Holds current page. Can be used by forms that POST to a different page, but want to return to current page afterward.
}
else{
    $view->pageTitle = 'User';
    $view->currentPage = 'user.php';
}


session_start(); //Starts the session

require_once('login_handler.php'); //Controls logging the user in, if user attempted to
if(isset($_SESSION['uID'])) {
    require_once('Models/Selected_User.php');
    $user = new Selected_User(); //Gets users for front page
    $view->userData = $user->fetch($_GET['selectedID']); //Fetches users based off given offset (depending on current page
    if($view->userData != null) {
        $view->friendship = $user->fetchFriendship($_SESSION['uID'], $_GET['selectedID']);
    }

}

if(isset($_POST['targetedUserID'])) { //If a POST was made to change friendship of users
    if(isset($_POST['send_fr_req'])) { //If user is sending friend request
        require_once('Models/FriendshipReqCreator.php'); //Create friend request
        $create = new FriendshipReqCreator($_SESSION['uID'],$_POST['targetedUserID']);

    }
    elseif (isset($_POST['acc_fr_req'])) { //If user is accepting friend request
        require_once('Models/Friendship_Confirm.php'); //Confirm friendship
        $confirm = new Friendship_Confirm($_SESSION['uID'],$_POST['targetedUserID']);
    }
    elseif (isset($_POST['rem_fr'])) { //If user is removing friendship
        require_once('Models/Rem_Friendship.php'); //If friendship has been ended, if sent friendship request is canceled, or received friendship request is declined
        $remove = new Rem_Friendship($_SESSION['uID'],$_POST['targetedUserID']);
    }
    header('location: '.$view->currentPage); //Refresh page
    exit();
}

if(isset($_POST['uploadImage'])) { //If user has uploaded a file
    $target_dir = "images/"; //Set Directory it will be saved to
    $target_file = $target_dir .$_SESSION['uID'].'.png'; //Save relative location where it will be saved (including file name)
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]); //Ensure upload is an image
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $view->pictureValid=false;
            $uploadOk = 0;
        }


// Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) { //Only accept image if it isnt too big
        $view->pictureValid=false;
        $uploadOk = 0;
    }

// Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") { //Only accept certain file extensions
        $view->pictureValid=false;
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        $view->pictureValid=false;
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) { //Upload image

        } else {
            $view->pictureValid=false;
        }
    }
    header('location: '.$view->currentPage); //Refresh page
    exit();
}

require_once('Views/user.phtml');
<?php
//Reusable code for page navigation
if(isset($_POST['pageNo'])) { //Sets view var to what page user is on, so correct users can be loaded.
    if(isset($_POST["first"])) { //If 'First' was clicked, go to page 1
        $view->currentPageNo = 1;
    }
    elseif(isset($_POST['prev'])) { //If 'Previous' was clicked, go to last page -1
        $view->currentPageNo = ($_POST['pageNo']-1);
    }
    elseif(isset($_POST['next'])){ //If 'next' was clicked, go to last page +1
        $view->currentPageNo = ($_POST['pageNo']+1);
    }
    elseif (isset($_POST['last'])) { //If 'last' was clicked, go to last page of users
        $view->currentPageNo = $_POST['lastPage'];
    }
    else { //Shouldn't occur, but if does, set currentPageNo to 1 to prevent error
        $view->currentPageNo = 1;
    }
}
else{ //If page navigation wasnt used, then current page is 1
    $view->currentPageNo = 1;
}
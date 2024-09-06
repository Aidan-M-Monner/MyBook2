<?php
    session_start();
    include("classes/classes.php");

    // ------- Check Login ------- //
    $login = new Login();
    $user_data = $login->check_login($_SESSION['mybook_user_id']);

    // ------- Check Post Information ------- //
    $Post = new Post();
    $likes = false; 
    $ERROR = "";
    $DB = new Database();
    if(isset($URL[2]) && isset($URL[1])) {
        $likes = $Post->get_likes($URL[2], $URL[1]);
    } else {
        $ERROR = "No information was found!";
    }

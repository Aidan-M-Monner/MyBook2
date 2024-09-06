<?php
    session_start();
    include("classes/classes.php");

    // ------- Check Login ------- //
    $login = new Login();
    $user_data = $login->check_login($_SESSION['mybook_user_id']);

    // ------- Return User Back To Previous Page ------- //
    if(isset($_SERVER['HTTP_REFERER']) && !strstr($_SERVER['HTTP_REFERER'], "/delete/")) {
        $_SESSION['return'] = $_SERVER['HTTP_REFERER'];
    } 
    
    // ------- Check Post Information ------- //
    $ERROR = "";
    $DB = new Database();
    if(isset($URL[1]) && is_numeric($URL[1])) {
        $Post = new Post();
        $ROW = $Post->get_post($URL[1]);

        if(!$ROW) {
            $ERROR = "No such post was found!";
        } else {
            if(!i_own_content($ROW)) {
                $ERROR = "Access denied! You cannot delete this post!";
            }
        }
    } else {
        $ERROR = "No such post was found!";
    }

    // ------- Content Was Posted ------- //
    if($ERROR == "" && $_SERVER['REQUEST_METHOD'] == "POST") {
        $Post->delete_post($_POST['post_id']);
        header("Location: " . $_SESSION['return']);
        die;
    }
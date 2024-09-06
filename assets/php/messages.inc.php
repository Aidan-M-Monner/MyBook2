<?php
    session_start();
    include("classes/classes.php");

    // ------- Check Login ------- //
    $login = new Login();
    $user_data = $login->check_login($_SESSION['mybook_user_id']);

    // ------- Message Variables ------- //
    $DB = new Database();
    $msg_class = new Messages();
    $user_class = new User();
    
    // ------- Check Post Information ------- //
    $ERROR = "";
    $profile_data = "";
    if(isset($URL[1]) && is_numeric($URL[1])) {
        $profile = new Profile();
        $profile_data = $profile->get_profile($URL[1]);
    }

    // ------- New Message - Check if Thread Exists ------- //
    if(isset($URL[1]) && $URL[1] == "new") {
        $old_thread = $msg_class->read($URL[2]);
        if(is_array($old_thread)) {
            // redirect the user
            header("Location: " . ROOT . "messages/read/" . $URL[2]);
        }
    }
    
    // ------- Messages Was Posted ------- //
    if($ERROR == "" && $_SERVER['REQUEST_METHOD'] == "POST") {
        if(is_array($user_class->get_user($URL[2]))) {
            $ERROR = $msg_class->send($_POST, $_FILES, $URL[2]);
    
            header("Location: " . ROOT . "messages/read/" . $URL[2]);
            die;
        } else {
            $ERROR = "The requested user could not be found!";
        }
    }
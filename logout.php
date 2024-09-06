<?php 
    session_start();

    if(isset($_SESSION['mybook_user_id'])) {
        $_SESSION['mybook_user_id'] = NULL;
        unset($_SESSION['mybook_user_id']);
    }
    
    header("Location: " . ROOT . "login");
    die;
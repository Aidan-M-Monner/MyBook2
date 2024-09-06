<?php
    session_start();
    include("classes/classes.php");

    // ------- Check Login ------- //
    $login = new Login();
    $user_data = $login->check_login($_SESSION['mybook_user_id']);

    $user = $user_data;
    if(isset($_GET['id']) && is_numeric($_GET['id'])) {
        $profile = new Profile();
        $profile_data = $profile->get_profile($_GET['id']);
    }

    $post = new Post();
    $user = new User();
    
    
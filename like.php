<?php 
    session_start();
    include("assets/php/classes/classes.php");

    // ------- Check Login ------- //
    $login = new Login();
    $user_data = $login->check_login($_SESSION['mybook_user_id']);

    // ------- Return User Back To Previous Page ------- //
    if(isset($_SERVER['HTTP_REFERER'])) {
        $return = $_SERVER['HTTP_REFERER'];
    } else {
        $return = "profile.php";
    }

    // ------- Like Content ------- //
    $_GET['type'] = isset($URL[1]) ? $URL[1] : null;
    $_GET['id'] = isset($URL[2]) ? $URL[2] : null;
    if(isset($_GET['type']) && isset($_GET['id'])) {
        if(is_numeric($_GET['id'])) {
            $allowed = ['post', 'user', 'comment'];
            if(in_array($_GET['type'], $allowed)) {
                $post = new Post();
                $user = new User();
                $post->like_post($_GET['id'], $_GET['type'], $_SESSION['mybook_user_id']);

                if($_GET['type'] == "user") {
                    $user->follow_user($_GET['id'], $_GET['type'], $_SESSION['mybook_user_id']);
                }
            }
        }
    }

    header("Location: " . $return);
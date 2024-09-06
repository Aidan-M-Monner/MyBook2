<?php
    session_start();
    include("classes/classes.php");

    // ------- Check Login ------- //
    $login = new Login();
    $user_data = $login->check_login($_SESSION['mybook_user_id']);

    // ------- Check Post Information ------- //
    $Post = new Post();
    $ROW = false; 
    $ERROR = "";
    $DB = new Database();
    if(isset($URL[1]) && is_numeric($URL[1])) {
        $ROW = $Post->get_post($URL[1]);
    } else {
        $ERROR = "No information was found!";
    }

    // ------- Create Posts ------- //
    if($_SERVER['REQUEST_METHOD'] == "POST") {

        if(isset($_POST['first_name'])) {
            $settings_class = new Settings();
            $settings_class->save_settings($_POST, $_SESSION['mybook_user_id']);
        } else {
            $post = new Post();
            $result = $post->create_post($_SESSION['mybook_user_id'], $_POST, $_FILES);

            if($result == "") {
                header("Location: " . ROOT . "single_post/$URL[1]");
                die;
            } else {
                echo "<div style='background-color: grey; color: #FFF; font-size: 12px; text-align: center;'>";
                echo "<br> The following errors occured: <hr>";
                echo $result;
                echo "<br></div>";
            }
        }
    }

    // check if notification has been seen
    if(isset($URL[2])) {
        notification_seen($URL[2]);
    }

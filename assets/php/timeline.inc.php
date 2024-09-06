<?php 
    session_start();
    include("classes/classes.php");

    // ------- Check Login ------- //
    $login = new Login();
    $user_data = $login->check_login($_SESSION['mybook_user_id']);

    // ------- Check Profile ------- //
    $profile = new Profile();

    if(isset($_GET['id']) && is_numeric($_GET['id'])) {
        $profile_data = $profile->get_profile($_GET['id']);

        if(is_array($profile_data)) {
            $profile_data = $profile_data[0];
        }

        if(empty($profile_data)) {
            $profile_data = $user_data;
        }
    } else {
        $profile_data = $user_data;
    }

    // ------- Profile Variables ------- //
    $user_name = $profile_data['first_name'] . " " . $profile_data['last_name'];
    $image = new Image();

    // ------- User Profile Img ------- //
    $profile_img = "";
    if($profile_data['gender'] == 'Male') {
        $profile_img = "assets/img/user_male.png";
    } else if($profile_data['gender'] == 'Female') {
        $profile_img = "assets/img/user_female.jpg";
    }

    if(file_exists($profile_data['profile_img'])) {
        $profile_img = $image->get_thumb_profile($profile_data['profile_img']);
    }
    
    // ------- Create Posts ------- //
    if($_SERVER['REQUEST_METHOD'] == "POST") {
        $post = new Post();
        $result = $post->create_post($_SESSION['mybook_user_id'], $_POST, $_FILES);

        if($result == "") {
            header("Location: index.php");
            die;
        } else {
            echo "<div style='background-color: grey; color: #FFF; font-size: 12px; text-align: center;'>";
            echo "<br> The following errors occured: <hr>";
            echo $result;
            echo "<br></div>";
        }
    }
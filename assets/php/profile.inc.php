<?php 
    session_start();
    include("classes/classes.php");

    // ------- Check Login ------- //
    $login = new Login();
    $_SESSION['mybook_user_id'] = isset($_SESSION['mybook_user_id']) ? $_SESSION['mybook_user_id'] : 0;
    $user_data = $login->check_login($_SESSION['mybook_user_id'], false);

    // ------- Check Profile ------- //
    $profile = new Profile();

    if(isset($URL[1]) && is_numeric($URL[1])) {
        $profile_data = $profile->get_profile($URL[1]);

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

    // ------- Create Posts ------- //
    if($_SERVER['REQUEST_METHOD'] == "POST") {
        include("profile_change_image.php");
        if(isset($_POST['first_name'])) {
            $settings_class = new Settings();
            $settings_class->save_settings($_POST, $_SESSION['mybook_user_id']);
        } else {
            $post = new Post();
            $result = $post->create_post($_SESSION['mybook_user_id'], $_POST, $_FILES);

            if($result == "") {
                header("Location:  " . ROOT . "profile");
                die;
            } else {
                if(($_GET['change'] != 'profile') && ($_GET['change'] != 'cover')) {
                    echo "<div style='background-color: grey; color: #FFF; font-size: 12px; text-align: center;'>";
                    echo "<br> The following errors occured: <hr>";
                    echo $result;
                    echo "<br></div>";
                }
            }
        }
    }

    // ------- Collect Posts ------- //
    $post = new Post();
    $posts = $post->get_posts($profile_data['user_id']);

    // ------- Collect Friends ------- //
    $user = new User();
    $id = $profile_data['user_id'];
    $friends = $user->get_following($profile_data['user_id'], "user");

    // ------- User Profile Img ------- //
    $profile_img = "";
    if($profile_data['gender'] == 'Male') {
        $profile_img = "assets/img/user_male.png";
    } else if($profile_data['gender'] == 'Female') {
        $profile_img = "assets/img/user_female.jpg";
    }

    if(file_exists($profile_data['profile_img'])) {
        $profile_img = $image->get_thumb_profile($profile_data['profile_img']);
    } else if(file_exists($profile_data['profile_img'] . "_profile_thumb.jpg")) {
        $profile_img = $profile_data['profile_img'] . "_profile_thumb.jpg";
    }

    // ------- User Cover Img ------- //
    $cover_img = "assets/img/mountain.jpg";
    if(file_exists($profile_data['cover_img'])) {
        $cover_img = $image->get_thumb_cover($profile_data['cover_img']);
    }

    // ------- User Followers ------- //
    $myFollowers = $profile_data['likes'];
    if($myFollowers > 1) {
        $myFollowers = $myFollowers . " Followers";
    } else if($myFollowers > 0) {
        $myFollowers = $myFollowers . " Follower";
    } else if($myFollowers == 0) {
        $myFollowers = $myFollowers . " Followers";
    }

    $i_follow = "Follow";
    if(isset($_SESSION['mybook_user_id'])) {
        $DB = new Database();

        // Save likes details
        $sql = "select likes from likes where type = 'user' && content_id = '$profile_data[user_id]' limit 1";
        $result = $DB->read($sql);

        if(is_array($result)) {
            $result_likes = json_decode($result[0]['likes'], true);

            if(is_array($result_likes)) {
                $user_ids = array_column($result_likes, 'user_id');
                if(in_array($_SESSION['mybook_user_id'], $user_ids)) {
                    $i_follow = "Followed";
                }
            }
        }
    }

    // check if notification has been seen
    if(isset($URL[3])) {
        notification_seen($URL[3]);
    }


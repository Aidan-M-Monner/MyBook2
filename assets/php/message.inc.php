<?php 
    // ------- Post Variables ------- //
    $Time = new Time();
    $message_id = $MESSAGE['msg_id'];
    $message_name = htmlspecialchars($MESSAGE_USER['first_name'] . " " . $MESSAGE_USER['last_name']);
    $message_text = check_tags($MESSAGE['message']);
    $message_image = $MESSAGE['file'];
    $message_date = $Time->get_time($MESSAGE['date']);
    $likes_message = "";
    
    // ------- User Pronoun ------- //
    $pronoun = "his";
    if($MESSAGE_USER['gender'] == "Female") {
        $pronoun = "her";
    }
    
    // ------- User Profile Image ------- //
    $user_post_img = "";
    if(file_exists($MESSAGE_USER['profile_img'])) {
        $user_post_img = $image->get_thumb_profile($MESSAGE_USER['profile_img']);
    } else if(file_exists($MESSAGE_USER['profile_img'] . "_profile_thumb.jpg")) {
        $user_post_img = $MESSAGE_USER['profile_img'] . "_profile_thumb.jpg";
    } else if($MESSAGE_USER['gender'] == "Male") {
        $user_post_img = "assets/img/user_male.png";
    } else if($MESSAGE_USER['gender'] == "Female") {
        $user_post_img = "assets/img/user_female.jpg";
    }
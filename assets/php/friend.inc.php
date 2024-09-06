<?php 
    // ------- Friend Variables ------- //
    $friend_id = $FRIEND_ROW['user_id'];
    $friend_name = $FRIEND_ROW['first_name'] . " " . $FRIEND_ROW['last_name'];
    
    // ------- Friend Profile Image ------- //
    $friend_img = "";
    if(file_exists($FRIEND_ROW['profile_img'])) {
        $friend_img = $image->get_thumb_profile($FRIEND_ROW['profile_img']);
    } else if(file_exists($FRIEND_ROW['profile_img'] . "_profile_thumb.jpg")) {
        $friend_img = $FRIEND_ROW['profile_img'] . "_profile_thumb.jpg";
    } else if($FRIEND_ROW['gender'] == "Male") {
        $friend_img = "assets/img/user_male.png";
    } else if($FRIEND_ROW['gender'] == "Female") {
        $friend_img = "assets/img/user_female.jpg";
    }

    // ------- Friend Last Online ------- //
    $friend_online = "Last seen: Unknown";
    if($FRIEND_ROW['online'] > 0) {
        $friend_online = $FRIEND_ROW['online'];

        $current_time = time();
        $threshold = 20; 
        if(($current_time - $friend_online) < $threshold) {
            $friend_online = "<span style='color: green;'>Online</span>";
        } else {
            $Time = new Time();
            $friend_online = "Last seen: " . $Time->get_time(date("Y-m-d H:i:s", $friend_online));
        }
    }
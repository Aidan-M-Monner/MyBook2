<?php
    // ------- Notification Variables ------- //
    $actor = $user->get_user($notif_row['user_id']);
    $owner = $user->get_user($notif_row['content_owner']);
    $id = esc($_SESSION['mybook_user_id']);
    $date = date("jS M Y H:i:s a", strtotime($notif_row['date']));
    $content_enjoyer = "profile/" . $notif_row['user_id'];

    // ------- Content Link (Post/Profile) ------- //
    $link = "";
    if($notif_row['content_type'] == "post") {
        $link = ROOT . "single_post/" . $notif_row['content_id'] . "/" . $notif_row['id'];
    } else if($notif_row['content_type'] == "profile") {
        $link = ROOT . "profile/" . $notif_row['content_id'] . "/default/" . $notif_row['id'];
    } else if($notif_row['content_type'] == "comment") {
        $link = ROOT . "single_post/" . $notif_row['content_id'] . "/" . $notif_row['id'];
    }

    // ------- User Profile Image ------- //
    $user_post_img = "";
    if(file_exists($actor['profile_img'])) {
        $user_post_img = $image->get_thumb_profile($actor['profile_img']);
    } else if(file_exists($actor['profile_img'] . "_profile_thumb.jpg")) {
        $user_post_img = $actor['profile_img'] . "_profile_thumb.jpg";
    } else if($actor['gender'] == "Male") {
        $user_post_img = "assets/img/user_male.png";
    } else if($actor['gender'] == "Female") {
        $user_post_img = "assets/img/user_female.jpg";
    }

    // ------- Notification Button Color ------- //
    $query = "select * from notification_seen where user_id = '$id' && notification_id = '$notif_row[id]' limit 1";
    $seen = $DB->read($query);
    
    if(is_array($seen)) {
        $color = "#eee";
    } else {
        $color = "#dfcccc";
    }

?>

<a href="<?php echo $link; ?>" style="text-decoration: none;">
    <div id="notification" style="background-color: <?= $color ?>">
        <?php
            if(is_array($actor) && is_array($owner)) {
                echo "<img src='" . ROOT . $user_post_img . "' style='float: left; margin: 2px; width: 36px;' />";
                
                echo "<a href='" . $content_enjoyer . "' style='text-decoration: none;'>";
                if($actor['user_id'] != $id) {
                    echo $actor['first_name'] . " " . $actor['last_name'];
                } else {
                    echo "You ";
                }
                echo "</a>";
                
                if($notif_row['activity'] == "like") {
                    echo " liked ";
                } else if($notif_row['activity'] == "follow") {
                    echo " followed ";
                } else if($notif_row['activity'] == "comment") {
                    echo " commented on ";
                } else if($notif_row['activity'] == "tag") {
                    echo " tagged ";
                }

                if($owner['user_id'] != $id && $notif_row['activity'] != "tag") {
                    echo $owner['first_name'] . " " . $owner['last_name'] . "'s ";
                } else if($notif_row['activity'] == "tag") {
                    echo " you in a ";
                } else {
                    echo " your ";
                }

                echo "<a href='" . $link . "' style='text-decoration: none;'>" . $notif_row['content_type'] . "</a>";
                echo "<br><span style='color: #888; display: inline-block; float: right; font-size: 11px; margin-right: 10px;'>" . $date . "</span>";
            }
        ?>
    </div>
</a>
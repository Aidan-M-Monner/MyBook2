<?php 
    // ------- Post Variables ------- //
    $post_id = $COMMENT['post_id'];
    $post_name = htmlspecialchars($COMMENT_USER['first_name'] . " " . $COMMENT_USER['last_name']);
    $post_text = check_tags($COMMENT['post']);
    $post_image = $COMMENT['image'];
    $post_date = $COMMENT['date'];
    $post_likes = $COMMENT['likes'];
    $likes_message = "";
    
    // ------- User Pronoun ------- //
    $pronoun = "his";
    if($COMMENT_USER['gender'] == "Female") {
        $pronoun = "her";
    }
    
    // ------- User Profile Image ------- //
    $user_post_img = "";
    if(file_exists($COMMENT_USER['profile_img'])) {
        $user_post_img = $image->get_thumb_profile($COMMENT_USER['profile_img']);
    } else if(file_exists($COMMENT_USER['profile_img'] . "_profile_thumb.jpg")) {
        $user_post_img = $COMMENT_USER['profile_img'] . "_profile_thumb.jpg";
    } else if($COMMENT_USER['gender'] == "Male") {
        $user_post_img = "assets/img/user_male.png";
    } else if($COMMENT_USER['gender'] == "Female") {
        $user_post_img = "assets/img/user_female.jpg";
    }

    // ------- Comment Likes ------- //
    $likes = "";
    $likes = ($post_likes > 0) ? "Likes(" . $post_likes . ")" : "Like";

    // ------- Likes Message ------- //
    $i_liked = false;
    if(isset($_SESSION['mybook_user_id'])) {
        $DB = new Database();

        // Save likes details
        $sql = "select likes from likes where type = 'post' && content_id = '$post_id' limit 1";
        $result = $DB->read($sql);

        if(is_array($result)) {
            $result_likes = json_decode($result[0]['likes'], true);

            $user_ids = array_column($result_likes, 'user_id');
            if(in_array($_SESSION['mybook_user_id'], $user_ids)) {
                $i_liked = true;
            }
        }
    }
    
    if($post_likes > 1) {
        if($i_liked && $post_likes > 2) {
            $likes_message = "<br><br> You and " . ($post_likes - 1) . " people like this comment.";
        } else if($i_liked && $post_likes > 1) {
            $likes_message = "<br><br> You and " . ($post_likes - 1) . " person like this comment.";
        } else {
            $likes_message = "<br><br>" . $post_likes . " people like this comment.";
        }
    } else if ($post_likes > 0) {
        if($i_liked) {
            $likes_message = "<br><br> You like this comment.";
        } else {
            $likes_message = "<br><br>" . $post_likes . " person likes this comment.";
        }
    }
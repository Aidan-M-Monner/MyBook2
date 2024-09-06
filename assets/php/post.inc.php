<?php 
    // ------- Post Variables ------- //
    $post_id = $ROW['post_id'];
    $post_name = htmlspecialchars($ROW_USER['first_name'] . " " . $ROW_USER['last_name']);
    $post_text = check_tags($ROW['post']);
    $post_image = $ROW['image'];
    $post_date = $ROW['date'];
    $post_likes = $ROW['likes'];
    $post_comments = $ROW['comments'];
    $comments = "";
    $likes_message = "";
    
    // ------- User Pronoun ------- //
    $pronoun = "his";
    if($ROW_USER['gender'] == "Female") {
        $pronoun = "her";
    }
    
    // ------- User Profile Image ------- //
    $user_post_img = "";
    if(file_exists($ROW_USER['profile_img'])) {
        $user_post_img = $image->get_thumb_profile($ROW_USER['profile_img']);
    } else if(file_exists($ROW_USER['profile_img'] . "_profile_thumb.jpg")) {
        $user_post_img = $ROW_USER['profile_img'] . "_profile_thumb.jpg";
    } else if($ROW_USER['gender'] == "Male") {
        $user_post_img = "assets/img/user_male.png";
    } else if($ROW_USER['gender'] == "Female") {
        $user_post_img = "assets/img/user_female.jpg";
    }

    // ------- Post Likes ------- //
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
            $likes_message = "<br><br> You and " . ($post_likes - 1) . " people like this post.";
        } else if($i_liked && $post_likes > 1) {
            $likes_message = "<br><br> You and " . ($post_likes - 1) . " person like this post.";
        } else {
            $likes_message = "<br><br>" . $post_likes . " people like this post.";
        }
    } else if ($post_likes > 0) {
        if($i_liked) {
            $likes_message = "<br><br> You like this post.";
        } else {
            $likes_message = "<br><br>" . $post_likes . " person likes this post.";
        }
    }

    // ------- Comments ------- //
    if($post_comments > 1) {
        $comments = "Comments(" . $ROW['comments'] . ")";
    } else if($post_comments > 0) {
        $comments = "Comment(" . $ROW['comments'] . ")";
    } else {
        $comments = "Comment";
    }

    // ------- Time ------- //
    $time = new Time();
    $post_date = $time->get_time($post_date);

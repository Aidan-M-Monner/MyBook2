<?php 
    session_start();

    // ------- Check Login ------- //
    $_SESSION['mybook_user_id'] = isset($_SESSION['mybook_user_id']) ? $_SESSION['mybook_user_id'] : 0;
    $login = new Login;
    $user_data = $login->check_login($_SESSION['mybook_user_id'], false);

    // If not logged in
    if($_SESSION['mybook_user_id'] == 0) {
        $obj = (object)[];
        $obj->action = "like_post";

        echo json_encode($obj);
        die;
    }

    // ------ Get query String From URL ------ //
    // $query_string = explode("?", $data->link);
    // $query_string = end($query_string);

    // $str = explode("&", $query_string);
    // foreach($str as $value) {
    //     $value = explode("=", $value);
    //     $_GET[$URL[1]] = $URL[2];
    // }

    $URL = split_url_from_string($data->link);
    $_GET['type'] = isset($URL[6]) ? $URL[6] : null;
    $_GET['id'] = isset($URL[7]) ? $URL[7] : null;

    // ------- Like Content ------- //
    $_GET['id'] = addslashes($_GET['id']);
    $_GET['type'] = addslashes($_GET['type']);

    if(isset($_GET['type']) && isset($_GET['id'])) {
        $post = new Post();
        $user = new User();
        if(is_numeric($_GET['id'])) {
            $allowed = ['post', 'user', 'comment'];
            if(in_array($_GET['type'], $allowed)) {
                $post->like_post($_GET['id'], $_GET['type'], $_SESSION['mybook_user_id']);

                if($_GET['type'] == "user") {
                    $user->follow_user($_GET['id'], $_GET['type'], $_SESSION['mybook_user_id']);
                }
            }
        }
        // ------- Read Likes ------- //
        $likes = $post->get_likes($_GET['id'], $_GET['type']);
        
        // ------- Likes Message ------- //
            $i_liked = false;
            $post_likes = count($likes);
            $info = "";

            if(isset($_SESSION['mybook_user_id'])) {
                $DB = new Database();

                // Save likes details
                $sql = "select likes from likes where type = 'post' && content_id = '$_GET[id]' limit 1";
                $result = $DB->read($sql);

                if(is_array($result)) {
                    $result_likes = json_decode($result[0]['likes'], true);

                    $user_ids = array_column($result_likes, 'user_id');
                    if(in_array($_SESSION['mybook_user_id'], $user_ids)) {
                        $i_liked = true;
                    }
                }
            }
            
            if($data->type == "post") {
                if($post_likes > 1) {
                    if($i_liked && $post_likes > 2) {
                        $info .= "<br><br> You and " . ($post_likes - 1) . " people like this post.";
                    } else if($i_liked && $post_likes > 1) {
                        $info .= "<br><br> You and " . ($post_likes - 1) . " person like this post.";
                    } else {
                        $info .= "<br><br>" . $post_likes . " people like this post.";
                    }
                } else if ($post_likes > 0) {
                    if($i_liked) {
                        $info .= "<br><br> You like this post.";
                    } else {
                        $info .= "<br><br>" . $post_likes . " person likes this post.";
                    }
                }
            } else if($data->type == "comment") {
                if($post_likes > 1) {
                    if($i_liked && $post_likes > 2) {
                        $info .= "<br><br> You and " . ($post_likes - 1) . " people like this comment.";
                    } else if($i_liked && $post_likes > 1) {
                        $info .= "<br><br> You and " . ($post_likes - 1) . " person like this comment.";
                    } else {
                        $info .= "<br><br>" . $post_likes . " people like this comment.";
                    }
                } else if ($post_likes > 0) {
                    if($i_liked) {
                        $info .= "<br><br> You like this comment.";
                    } else {
                        $info .= "<br><br>" . $post_likes . " person likes this comment.";
                    }
                }
            }


        $obj = (object)[];
        $obj->likes = count($likes);
        $obj->action = "like_post";
        $obj->info = $info;
        $obj->id = "info_" . $_GET['id']; 

        echo json_encode($obj);
    }
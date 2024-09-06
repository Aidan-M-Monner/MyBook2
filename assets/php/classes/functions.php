<?php 
    function esc($value) {
        return addslashes($value);
    }

    function show($data) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }

    // ------ Turing URL Into an Array ------ //
    $URL = split_url_array();
    function split_url_array() {
        $url = isset($_GET['url']) ? $_GET['url'] : "index";
        $url = explode("/", filter_var(trim($url, "/"), FILTER_SANITIZE_URL));
        return $url;
    }

    // ------ Turing URL Into an Array From String ------ //
    function split_url_from_string($str) {
        $url = isset($str) ? $str : "index";
        $url = explode("/", filter_var(trim($url, "/"), FILTER_SANITIZE_URL));
        return $url;
    }

    // ------ Check User Online ------ //
    function set_online($id) {
        if(!is_numeric($id)) {
            return;
        }

        $online = time();
        $DB = new database();
        $query = "update users set online = '$online' where user_id = '$id' limit 1";
        $user_row = $DB->save($query);
    }

    if(isset($_SESSION['mybook_user_id'])) {
        set_online($_SESSION['mybook_user_id']);
    }
    
    // ------ Check Page ------ //
    function pagination_link() {
        $page_number = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page_number = ($page_number < 1) ? 1 : $page_number;
        
        $arr['next_page'] = "";
        $arr['prev_page'] = "";
        
        // Get current URL
        // $url = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'];
        $url = ROOT . $_GET['url'];
        $url .= "?";

        $next_page_link = $url;
        $prev_page_link = $url;
        $page_found = false;

        $num = 0;
        foreach ($_GET as $key => $value) {
            if($key == 'url') {
                continue; //skips url
            }
            $num++;
            if($num == 1) {
                if($key == "page") {
                    $next_page_link .= $key . "=" . ($page_number + 1);
                    $prev_page_link .= $key . "=" . ($page_number - 1);
                    $page_found = true;
                } else {
                    $next_page_link .= $key . "=" . $value;
                    $prev_page_link .= $key . "=" . $value;
                }
            } else {
                if($key == "page") {
                    $next_page_link .= "&" . $key . "=" . ($page_number + 1);
                    $prev_page_link .= "&" . $key . "=" . ($page_number - 1);
                    $page_found = true;
                } else {
                    $next_page_link .= "&" . $key . "=" . $value;
                    $prev_page_link .= "&" . $key . "=" . $value;
                }
            }
        }

        $arr['next_page'] = $next_page_link;
        $arr['prev_page'] = $prev_page_link;

        if(!$page_found) {
            $arr['next_page'] = $next_page_link . "&page=2";
            $arr['prev_page'] = $prev_page_link . "&page=1";
        }

        return $arr;
    }

    // ------ Do I Own Content? ------ //
    function i_own_content($row) {
        $myId = $_SESSION['mybook_user_id'];

        // Checking if own profile
        if(isset($row['gender']) && $myId == $row['user_id']) {
            return true;
        }

        // Checking if own post / comment
        if(isset($row['post_id'])) {
            if($myId == $row['user_id']) {
                return true;
            } else {
                $Post = new Post();
                $one_post = $Post->get_post($row['parent']);

                if($myId == $row['user_id']) {
                    return true;
                }
            }
        }
        return false;
    }

    // ------ Add Notification ------ //
    function add_notification($user_id, $activity, $row, $tagged_user = '') {
        $row = (object)$row;
        $user_id = esc($user_id);
        $activity = esc($activity);
        $content_owner = $row->user_id;

        if($tagged_user != "") {
            $content_owner = $tagged_user;
        }

        $date = date("Y-m-d H:i:s");
        $content_id = 0;
        $content_type = "";

        if(isset($row->post_id)) {
            $content_id = $row->post_id;
            $content_type = "post";

            if($row->parent > 0) {
                $content_type = "comment";
            }
        }

        if(isset($row->gender)) {
            $content_type = "profile";
            $content_id = $row->user_id;
        }

        $DB = new Database();
        $query = "insert into notifications (user_id, activity, content_id, content_owner, content_type, date) values ('$user_id', '$activity', '$content_id', '$content_owner', '$content_type', '$date')";
        $DB->save($query);
    }

    // ------ Grab Notifications For Content I Follow ------ //
    function content_i_follow($user_id, $row) {
        $row = (object)$row;
        $user_id = esc($user_id);
        $date = date("Y-m-d H:i:s");
        $content_id = 0;
        $content_type = "";

        if(isset($row->post_id)) {
            $content_id = $row->post_id;
            $content_type = "post";

            if($row->parent > 0) {
                $content_type = "comment";
            }
        }

        if(isset($row->gender)) {
            $content_type = "profile";
        }

        $DB = new Database();
        $query = "insert into followed_content (user_id, content_id, content_type, date) values ('$user_id', '$content_id', '$content_type', '$date')";
        $DB->save($query);
    }

    // ------ Notifications Checked ------ //
    function check_notifications() {
        $number = 0;
        $user_id = $_SESSION['mybook_user_id'];
        $DB = new Database();

        // Check the content I follow
        $followed = array();
        $sql = "select * from followed_content where disabled = 0 && user_id = '$user_id' limit 100";
        $i_follow = $DB->read($sql);
        if(is_array($i_follow)) {
            $followed = array_column($i_follow, "content_id");
        }

        if(count($followed) > 0) {
            $str = "'" . implode("','", $followed) . "'";
            $query = "select * from notifications where (user_id != '$user_id' && content_owner = '$user_id') || (content_id in ($str)) order by id desc limit 30";
        } else {
            $query = "select * from notifications where user_id != '$user_id' && content_owner = '$user_id' order by id desc limit 30";
        }

        $data = $DB->read($query);

        if(is_array($data)) {
            foreach($data as $row) {
                // Check table for the notification
                $query = "select * from notification_seen where user_id = '$user_id' && notification_id = '$row[id]' limit 1";
                $check = $DB->read($query);

                // User clicked on notification -> Save 
                if(!is_array($check)) {
                    $number++;
                }
            }
        }

        return $number;
    }
    
    // ------ Notifications Have Been Seen ------ //
    function notification_seen($id) {
        $notification_id = addslashes($id);
        $user_id = $_SESSION['mybook_user_id'];

        $DB = new Database();

        // Check table for the notification
        $query = "select * from notification_seen where user_id = '$user_id' && notification_id = '$notification_id' limit 1";
        $check = $DB->read($query);

        // User clicked on notification -> Save 
        if(!is_array($check)) {
            $query = "insert into notification_seen (user_id, notification_id) values ('$user_id', '$notification_id')";
            $DB->save($query);
        }
    }

    // ------ Check Post For tag ------ //
    function check_tags($text) {
        $str = "";
        $words = explode(" ", $text);
        $DB = new Database();
        if(is_array($words) && count($words) > 0) {
            foreach($words as $word) {
                if(preg_match("/@[a-zA-Z_0-9\Q,.?!\E]+/", $word)) {
                    $word = trim($word, '@');
                    $word = trim($word, ',');
                    $tag_name = esc(trim($word, '.'));
                    $query = "select * from users where tag_name = '$tag_name' limit 1";
                    $user_row = $DB->read($query);
                    
                    if(is_array($user_row)) {
                        $user_row = $user_row[0];
                        $str .= "<a href='" . ROOT . "profile/$user_row[user_id]'>@" . $word . "</a> ";
                    } else {
                        $str .= htmlspecialchars($word) . " ";
                    }
                } else {
                    $str .= htmlspecialchars($word) . " ";
                }
            }
        }
        if($str != "") {
            return $str;
        }
        return htmlspecialchars($text);
    }

    // ------ Find Multiple tags ------ //
    function get_tags($text) {
        $tags = array();
        $words = explode(" ", $text);
        $DB = new Database();
        if(is_array($words) && count($words) > 0) {
            foreach($words as $word) {
                if(preg_match("/@[a-zA-Z_0-9\Q,.?!\E]+/", $word)) {
                    $word = trim($word, '@');
                    $word = trim($word, ',');
                    $tag_name = esc(trim($word, '.'));
                    $query = "select * from users where tag_name = '$tag_name' limit 1";
                    $user_row = $DB->read($query);
                    
                    if(is_array($user_row)) {
                        $tags[] = $word;
                    }
                }
            }
        }
        return $tags;
    }

    // ------ Save Tags into Notifications ------ //
    function tag($post_id, $new_post_text = "") {
        $DB = new Database();
        $sql = "select * from posts where post_id = '$post_id' limit 1";
        $myPost = $DB->read($sql);

        if(is_array($myPost)) {
            $myPost = $myPost[0];

            // For Editing
            if($new_post_text) {
                $old_post = $myPost;
                $myPost['post'] = $new_post_text;
            }

            $tags = get_tags($myPost['post']);
            foreach($tags as $tag) {
                $sql = "select * from users where tag_name = '$tag' limit 1";
                $tagged_user = $DB->read($sql);
                if(is_array($tagged_user)) {
                    $tagged_user = $tagged_user[0];

                    if($new_post_text != "") {
                        $old_tags = get_tags($old_post['post']);
                        if(!in_array($tagged_user['tag_name'], $old_tags)) {
                            add_notification($_SESSION['mybook_user_id'], "tag", $myPost, $tagged_user['user_id']);
                        }
                    } else {
                        add_notification($_SESSION['mybook_user_id'], "tag", $myPost, $tagged_user['user_id']);
                    }
                }
            }
        }
    }
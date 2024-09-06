<?php 
    class User {
        // ------- Get User Data ------- //
        public function get_data($id) {
            $DB = new Database();
            $query = "select * from users where user_id = '$id' limit 1";
            $result = $DB->read($query);

            if($result) {
                $row = $result[0];
                return $row;
            } else {
                return false;
            }
        }

        // ------- Find The User ------- //
        public function get_user($id) {
            $DB = new Database();
            $query = "select * from users where user_id = '$id' limit 1";
            $result = $DB->read($query);

            if($result) {
                return $result[0];
            } else {
                return false;
            }
        }

        // ------- Find The User's Friends ------- //
        public function get_friends($id) {
            $DB = new Database();
            $query = "select * from users where user_id != '$id'";
            $result = $DB->read($query);

            if($result) {
                return $result;
            } else {
                return false;
            }
        }

        // ------- Follow User ------- //
        public function follow_user($id, $type, $user_id) {
            $DB = new Database();

            // Save likes details
            $sql = "select following from likes where type = '$type' && content_id = '$user_id' limit 1";
            $result = $DB->read($sql);

            if(is_array($result)) {
                $follows = json_decode($result[0]['following'], true);

                if($follows == null) {
                    $arr['user_id'] = $id;
                    $arr['date'] = date("Y-m-d H:i:s");

                    $follows[] = $arr;
                    $follow_string = json_encode($follows);

                    // Save user/follow data
                    $sql = "update likes set following = '$follow_string' where type = '$type' && content_id = '$user_id' limit 1";
                    $result = $DB->save($sql);

                    // Add a notification
                    $user = new User();
                    $single_user = $user->get_user($id);
                    add_notification($_SESSION['mybook_user_id'], "follow", $single_user);
                } else {
                    $user_ids = array_column($follows, 'user_id');
                    if(!in_array($id, $user_ids)) {
                        $arr['user_id'] = $id;
                        $arr['date'] = date("Y-m-d H:i:s");

                        $follows[] = $arr;
                        $follow_string = json_encode($follows);
                        $sql = "update likes set following = '$follow_string' where type = '$type' && content_id = '$user_id' limit 1";
                        $result = $DB->save($sql);

                        // Add a notification
                        $user = new User();
                        $single_user = $user->get_user($id);
                        add_notification($_SESSION['mybook_user_id'], "follow", $single_user);
                    } else {
                        $key = array_search($id, $user_ids);
                        unset($follows[$key]);

                        $follow_string = json_encode($follows);
                        $sql = "update likes set following = '$follow_string' where type = '$type' && content_id = '$user_id' limit 1";
                        $result = $DB->save($sql);
                    }
                }
            } else {
                $arr['user_id'] = $id;
                $arr['date'] = date("Y-m-d H:i:s");
                $arr2[] = $arr;

                $following = json_encode($arr2);
                $sql = "insert into likes (type, content_id, following) values ('$type', '$user_id', '$following')";
                $result = $DB->save($sql);

                // Add a notification
                $user = new User();
                $single_user = $user->get_user($id);
                add_notification($_SESSION['mybook_user_id'], "follow", $single_user);
            }
        }

        // ------- Display Followers ------- //
        public function get_following($id, $type) {
            if(is_numeric($id)) {
                $DB = new Database();

                // Get follow details
                $sql = "select following from likes where type = '$type' && content_id = '$id' limit 1";
                $result = $DB->read($sql);

                if(is_array($result)) {
                    $following = json_decode($result[0]['following'], true);
                    return $following;
                }
            }
            return false;
        }
    }
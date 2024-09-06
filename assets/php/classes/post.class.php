<?php 
    class Post {
        // ------- Post Variables ------- //
        private $error = "";

        // ------- Create Post ------- //
        public function create_post($user_id, $data, $files) {
            if(!empty($data['post']) || !empty($files['file']['name']) || isset($data['profile_img']) || isset($data['cover_img'])) {
                $myImage = "";
                $has_image = 0;
                $profile_img = 0;
                $cover_img = 0;
                $DB = new Database();

                if(isset($data['profile_img']) || isset($data['cover_img'])) {
                    $myImage = $files;
                    $has_image = 1;

                    if(isset($data['profile_img'])) {
                        $profile_img = 1;
                    }

                    if(isset($data['cover_img'])) {
                        $cover_img = 1;
                    }
                } else {
                    if(!empty($files['file']['name'])) {
                        $image = new Image();

                        $folder = "uploads/" . $user_id . "/";
                        if(!file_exists($folder)) {
                            mkdir($folder, 0777, true);
                            file_put_contents($folder, "index.php", "");
                        }

                        $allowed[] = "image/jpeg";
                        if(in_array($files['file']['type'], $allowed)) {
                            $myImage = $folder . $image->generate_filename(15) . ".jpg";
                            move_uploaded_file($files['file']['tmp_name'], $myImage);
                            $image->resize_image_jpeg($myImage, $myImage, 1500, 1500);

                            $has_image = 1;
                        } else {
                            $this->error .= "The selected image is not valid type!<br>";
                        }
                    }
                }

                $post = "";
                if(isset($data['post'])) {
                    $post = addslashes($data['post']);
                }

                // Add tagged users
                $tags = array();
                $tags = get_tags($post);
                $tags = json_encode($tags);

                if($this->error == "") {
                    $post_id = $this->create_post_id();
                    $parent = 0;

                    if(isset($data['parent']) && is_numeric($data['parent'])) {
                        $parent = $data['parent'];
                        $mypost = $this->get_post($data['parent']);

                        if(is_array($mypost) && $mypost['user_id'] != $user_id) {
                            // Follow this item
                            content_i_follow($user_id, $mypost);
                            
                            // add notification
                            add_notification($_SESSION['mybook_user_id'], "comment", $mypost);
                        }

                        $sql = "update posts set comments = comments + 1 where post_id = $parent limit 1";
                        $DB->save($sql);
                    }

                    $query = "insert into posts (user_id, post_id, parent, post, tags, has_image, profile_img, cover_img, image) values ('$user_id', '$post_id', '$parent', '$post', '$tags', '$has_image', '$profile_img', '$cover_img', '$myImage')";
                    $DB->save($query);

                    // Notify those that were tagged
                    tag($post_id);
                }
            } else {
                $this->error .= "Please type something to post!<br>";
            }

            return $this->error;
        }

        // ------- Create Post ID ------- //
        private function create_post_id() {
            $len = rand(4, 19);
            $num = "";

            for($i = 0; $i < $len; $i++) {
                $new_rand = rand(0,9);
                $num = $num . $new_rand;
            }

            return $num;
        }

        // ------- Load Posts ------- //
        public function get_posts($id) {
            $page_number = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $page_number = ($page_number < 1) ? 1 : $page_number;

            $limit = 3;
            $offset = ($page_number - 1) * $limit;


            $DB = new Database();
            $query = "select * from posts where parent = 0 and user_id = '$id' order by id desc limit $limit offset $offset";
            $result = $DB->read($query);

            if($result) {
                return $result;
            } else {
                return false;
            }
        }

        // ------- Load Comments ------- //
        public function get_comments($id) {
            $page_number = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $page_number = ($page_number < 1) ? 1 : $page_number;

            $limit = 3;
            $offset = ($page_number - 1) * $limit;

            $DB = new Database();
            $query = "select * from posts where parent = '$id' order by id asc limit $limit offset $offset";
            $result = $DB->read($query);

            if($result) {
                return $result;
            } else {
                return false;
            }
        }

        // ------- Load a Post ------- //
        public function get_post($post_id) {
            if(!is_numeric($post_id)) {
                return false;
            }
            
            $DB = new Database();
            $query = "select * from posts where post_id = '$post_id' limit 1";
            $result = $DB->read($query);

            if($result) {
                return $result[0];
            } else {
                return false;
            }
        }

        // ------- Delete a Post ------- //
        public function delete_post($post_id) {
            if(!is_numeric($post_id)) {
                return false;
            }

            $Post = new Post();
            $one_post = $Post->get_post($post_id);

            $DB = new Database();
            $sql = "select parent from posts where post_id = '$post_id' limit 1";
            $result = $DB->read($sql);
            if(is_array($result)) {
                if($result[0]['parent'] > 0) {
                    $parent = $result[0]['parent'];
                    $sql = "update posts set comments = comments - 1 where post_id = '$parent' limit 1";
                    $DB->save($sql);
                }    
            }

            $query = "delete from posts where post_id = '$post_id' limit 1";
            $DB->save($query);

            if($one_post['image'] != "" && file_exists($one_post['image'])) {
                unlink($one_post['image']);
            }

            if($one_post['image'] != "" && file_exists($one_post['image'] . "_post_thumb.jpg")) {
                unlink($one_post['image'] . "_post_thumb.jpg");
            }

            if($one_post['image'] != "" && file_exists($one_post['image'] . "_cover_thumb.jpg")) {
                unlink($one_post['image'] . "_cover_thumb.jpg");
            }

            // delete all comments
            $query = "delete from posts where parent = '$post_id'";
            $DB->save($query);
        }

        // ------- Edit Post ------- //
        public function edit_post($data, $files) {
            if(!empty($data['post']) || !empty($files['file']['name'])) {
                $myImage = "";
                $has_image = 0;

                if(!empty($files['file']['name'])) {
                    $image = new Image();

                    $folder = "uploads/" . $user_id . "/";
                    if(!file_exists($folder)) {
                        mkdir($folder, 0777, true);
                        file_put_contents($folder, "index.php", "");
                    }
                    $myImage = $folder . $image->generate_filename(15) . ".jpg";
                    move_uploaded_file($_FILES['file']['tmp_name'], $myImage);
                    $image->resize_image_jpeg($myImage, $myImage, 1500, 1500);

                    $has_image = 1;
                }

                $post = "";
                if(isset($data['post'])) {
                    $post = addslashes($data['post']);
                }

                // Add tagged users
                $tags = array();
                $tags = get_tags($post);
                $tags = json_encode($tags);

                $post_id = addslashes($data['post_id']);

                $DB = new Database();
                if($has_image) {
                    $query = "update posts set post = '$post', image = '$myImage' where post_id = '$post_id' limit 1";
                } else {
                    $query = "update posts set post = '$post' where post_id = '$post_id' limit 1";
                }

                $DB->save($query);

                // Notify those that were tagged
                tag($post_id, $post);

            } else {
                $this->error .= "Please type something to post!<br>";
            }

            return $this->error;
        }

        // ------- Does User Own Post ------- //
        public function post_owner($post_id, $user_id) {
            
            
            if(!is_numeric($post_id)) {
                return false;
            }
            
            $DB = new Database();
            $query = "select * from posts where post_id = '$post_id' limit 1";
            $result = $DB->read($query);

            if(is_array($result)) {
                if($result[0]['user_id'] == $user_id) {
                    return true;
                }
            }

            return false;
        }

        // ------- Like Post ------- //
        public function like_post($id, $type, $user_id) {
            $DB = new Database();

            // Save likes details
            $sql = "select likes from likes where type = '$type' && content_id = '$id' limit 1";
            $result = $DB->read($sql);

            if(is_array($result)) {
                $likes = json_decode($result[0]['likes'], true);

                if($likes == null) {
                    $arr['user_id'] = $user_id;
                    $arr['date'] = date("Y-m-d H:i:s");

                    $likes[] = $arr;
                    $likes_string = json_encode($likes);

                    // Save user/like data
                    $sql = "update likes set likes = '$likes_string' where type='$type' && content_id = '$id' limit 1";
                    $result = $DB->save($sql);

                    // Increment the Posts table
                    $sql = "update {$type}s set likes = likes + 1 where {$type}_id = '$id' limit 1";
                    $DB->save($sql);

                    // Add a notification
                    if($type != "user") {
                        $post = new Post();
                        $single_post = $post->get_post($id);
                        add_notification($_SESSION['mybook_user_id'], "like", $single_post);
                    }
                } else {
                    $user_ids = array_column($likes, 'user_id');
                    if(!in_array($user_id, $user_ids)) {
                        $arr['user_id'] = $user_id;
                        $arr['date'] = date("Y-m-d H:i:s");

                        $likes[] = $arr;
                        $likes_string = json_encode($likes);
                        $sql = "update likes set likes = '$likes_string' where type='$type' && content_id = '$id' limit 1";
                        $DB->save($sql);

                        // Increment the right table
                        $sql = "update {$type}s set likes = likes + 1 where {$type}_id = '$id' limit 1";
                        $DB->save($sql);

                        // Add a notification
                        if($type != "user") {
                            $post = new Post();
                            $single_post = $post->get_post($id);
                            add_notification($_SESSION['mybook_user_id'], "like", $single_post);
                        }
                    } else {
                        $key = array_search($user_id, $user_ids);
                        unset($likes[$key]);

                        $likes_string = json_encode($likes);
                        $sql = "update likes set likes = '$likes_string' where type='$type' && content_id='$id' limit 1";
                        $DB->save($sql);

                        // Decrement the right table
                        $sql = "update {$type}s set likes=likes - 1 where {$type}_id='$id' limit 1";
                        $DB->save($sql);
                    }
                }
            } else {
                $arr['user_id'] = $user_id;
                $arr['date'] = date("Y-m-d H:i:s");
                $arr2[] = $arr;

                $likes = json_encode($arr2);
                $sql = "insert into likes (type, content_id, likes) values ('$type', '$id', '$likes')";
                $DB->save($sql);

                // Increment the right table
                $sql = "update {$type}s set likes = likes + 1 where {$type}_id = '$id' limit 1";
                $DB->save($sql);

                // Add a notification
                if($type != "user") {
                    $post = new Post();
                    $single_post = $post->get_post($id);
                    add_notification($_SESSION['mybook_user_id'], "like", $single_post);
                }
            }
        }

        // ------- Display Likes ------- //
        public function get_likes($id, $type) {
            if(is_numeric($id)) {
                $DB = new Database();

                // Get like details
                $sql = "select likes from likes where type = '$type' && content_id = '$id' limit 1";
                $result = $DB->read($sql);

                if(is_array($result)) {
                    $likes = json_decode($result[0]['likes'], true);
                    return $likes;
                }
            }
            return false;
        }

    }
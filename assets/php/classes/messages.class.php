<?php 
    Class Messages {
        // ------- Post Variables ------- //
        private $error = "";

        // ------- Create Post ------- //
        public function send($data, $files, $receiver) {
            if(!empty($data['message']) || !empty($files['file']['name'])) {
                $myImage = "";
                $has_image = 0;
                $DB = new Database();

                if(!empty($files['file']['name'])) {
                    $image = new Image();

                    $user_id = $_SESSION['mybook_user_id'];
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

                $message = "";
                if(isset($data['message'])) {
                    $message = esc($data['message']);
                }

                // Add tagged users
                $tags = array();
                $tags = get_tags($message);
                $tags = json_encode($tags);

                if(trim($message) == "" && $has_image == 0) {
                    $this->error .= "Please type something to send!<br>";
                }

                if($this->error == "") {
                    $msg_id = $this->create_msg_id(60);
                    $sender = esc($_SESSION['mybook_user_id']);
                    $receiver = esc($receiver);
                    $file = esc($myImage);

                    // Check if thread exists
                    $query = "select * from messages where (sender = '$sender' && receiver = '$receiver') || (receiver = '$sender' && sender = '$receiver') limit 1";
                    $data = $DB->read($query);

                    if(is_array($data)) {
                        $msg_id = $data[0]['msg_id'];
                    }

                    // Insert into database
                    $query = "insert into messages (sender, msg_id, receiver, message, file, tags) values ('$sender', '$msg_id', '$receiver', '$message', '$file', '$tags')";
                    $DB->save($query);

                    // Notify those that were tagged
                    // tag($msg_id);
                }
            } else {
                $this->error .= "Please type something to send!<br>";
            }

            return $this->error;
        }

        public function read($user_id) {
            $DB = new Database();
            $me = esc($_SESSION['mybook_user_id']);
            $user_id = esc($user_id);
            
            $query = "select * from messages where (sender = '$me' && receiver = '$user_id') || (receiver = '$me' && sender = '$user_id') order by id desc limit 20";
            $data = $DB->read($query);

            return $data;
        }

        public function create_msg_id($len) {
            $array = [0,1,2,3,4,5,6,7,8,9,'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','-','_'];
            $text = "";
            $len = rand(4, $len);

            for($x = 0; $x < $len; $x++) {
                $rand = rand(0,63);
                $text .= $array[$rand];
            }

            return $text;
        }
    }
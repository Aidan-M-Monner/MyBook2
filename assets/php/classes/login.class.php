<?php
    class Login {
        // ------- Signup Variables ------- //
        private $error = "";
        
        // ------- Check For User ------- //
        public function evaluate($data) {
            // --- Data Variables --- //
            $email = addslashes($data['email']);
            $password = addslashes($data['password']);

            // --- Insert Variables Into Table --- //
            $query = "select * from users where email = '$email' limit 1";
            $DB = new Database();
            $result = $DB->read($query);

            if($result) {
                $row = $result[0];
                if($this->hash_text($password) == $row['password']) {
                    // Create session data
                    $_SESSION['mybook_user_id'] = $row['user_id'];
                } else {
                    $this->error .= "Wrong email or password.<br>";
                }
            } else {
                $this->error .= "Wrong email or password.<br>";
            }

            return $this->error;
        }

        // ------ Check If Logged In ------ //
        public function check_login($id, $redirect = true) {
            if(is_numeric($_SESSION['mybook_user_id'])) {
                $query = "select * from users where user_id = '$id' limit 1";

                $DB = new Database();
                $result = $DB->read($query);

                if($result) {
                    $user_data = $result[0];
                    return $user_data;
                } else {
                    if($redirect) {
                        header("Location: " . ROOT . "login");
                        die;
                    } else {
                        $_SESSION['mybook_user_id'] = 0;
                    }
                }
            } else {
                if($redirect) {
                    header("Location: " . ROOT . "login");
                    die;
                } else {
                    $_SESSION['mybook_user_id'] = 0;
                }
            }
        }

        // ------ Encrypt the Password ------ //
        private function hash_text($text) {
            $text = hash("sha1", $text);
            return $text;
        }
    }

<?php
    class Signup {
        // ------- Signup Variables ------- //
        private $error = "";
        private $password = "";
        
        // ------- Evaluate the Data ------- //
        public function evaluate($data) {
            $DB = new Database();
            // --- Check To Find Empty Variable --- //
            foreach ($data as $key => $value) {
                if(empty($value)) {
                    $this->error = $this->error . $key . " is empty.<br>";
                }

                if($key == "first_name") {
                    if(is_numeric($value)) {
                        $this->error = $this->error . "First name cannot include numbers.<br>";
                    }

                    if(strstr($value, " ")) {
                        $this->error = $this->error . "First name cannot have spaces.<br>";
                    }
                }

                if($key == "last_name") {
                    if(is_numeric($value)) {
                        $this->error = $this->error . "Last name cannot include numbers.<br>";
                    }

                    if(strstr($value, " ")) {
                        $this->error = $this->error . "Last name cannot have spaces.<br>";
                    }
                }

                if($key == "gender") {
                    if($value == "Gender") {
                        $this->error = $this->error . "Please select a gender.<br>";
                    }
                }

                if($key == "email") {
                    if(!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $value)) {
                        $this->error = $this->error . "Invalid email address.<br>";
                    }
                }

                if($key == "password") {
                    $this->password = $value;
                }

                if($key == "password2") {
                    if($value != $this->password) {
                        $this->error = $this->error . "Passwords do not match.<br>";
                    }
                }
            }

            // Check Tag Name
            $data['tag_name'] = strtolower($data['first_name']) . strtolower($data['last_name']);
            $sql = "select id from users where tag_name = '$data[tag_name]' limit 1";
            $check = $DB->read($sql);
            while(is_array($check)) {
                $data['tag_name'] = strtolower($data['first_name']) . strtolower($data['last_name']) . rand(0,9999);
                $sql = "select id from users where tag_name = '$data[tag_name]' limit 1";
                $check = $DB->read($sql);
            }

            // Check User ID
            $data['user_id'] = $this->create_id();
            $sql = "select id from users where user_id = '$data[user_id]' limit 1";
            $check = $DB->read($sql);
            while(is_array($check)) {
                $data['user_id'] = $this->create_id();
                $sql = "select id from users where user_id = '$data[user_id]' limit 1";
                $check = $DB->read($sql);
            }

            // Check Email
            $sql = "select id from users where email = '$data[email]' limit 1";
            $check = $DB->read($sql);
            if(is_array($check)) {
                $this->error = $this->error . "Email is already in use.<br>";
            }

            // --- Check If There Is Error --- //
            if($this->error == "") {
                // no error 
                $this->create_user($data);
            } else {
                return $this->error;
            }
        }

        // ------- Create User ------- //
        public function create_user($data) {
            // --- Data Variables --- //
            $first_name = ucfirst($data['first_name']);
            $last_name = ucfirst($data['last_name']);
            $gender = $data['gender'];
            $email = $data['email'];
            $password = hash("sha1", $data['password']);
            $user_id = $data['user_id'];
            $tag_name = $data['tag_name'];

            // --- Created Variables --- //
            $url_address = strtolower($first_name) . "." . strtolower($last_name);

            // --- Insert Variables Into Table --- //
            $query = "insert into users (user_id, first_name, last_name, gender, email, password, url_address, tag_name) values ('$user_id', '$first_name', '$last_name', '$gender', '$email', '$password', '$url_address', '$tag_name')";
            $DB = new Database();
            $DB->save($query);
        }

        // ------- Create User ID ------- //
        private function create_id() {
            $len = rand(4, 19);
            $num = "";

            for($i = 0; $i < $len; $i++) {
                $new_rand = rand(0,9);
                $num = $num . $new_rand;
            }

            return $num;
        }
    }

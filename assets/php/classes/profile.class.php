<?php 
    Class Profile {
        // ------ Grab user Profile ------ //
        function get_profile($id) {
            // --- Take out special characters --- //
            $id = addslashes($id);

            // --- Grab profile from database --- //
            $DB = new Database();
            $query = "select * from users where user_id = '$id' limit 1";
            return $DB->read($query);
        }
    }
<?php 

    class Database {
        // ------- Connection Variables ------- //
        private $host = "localhost";
        private $username = "root";
        private $password = "";
        private $db = "mybook_db";

        // ------- Connect To Database ------- //
        function connect() {
            $connection = mysqli_connect($this->host, $this->username, $this->password, $this->db);
            return $connection;
        }

        // ------- Read From Database ------- //
        function read($query) {
            $conn = $this->connect();
            $result = mysqli_query($conn, $query);

            if(!$result) {
                return false;
            } else {
                $data = false;
                while($row = mysqli_fetch_assoc($result)) {
                    $data[] = $row;
                }
                return $data;
            }
        }

        // ------- Save Data To Database ------- //
        function save($query) {
            $conn = $this->connect();
            $result = mysqli_query($conn, $query);

            if(!$result) {
                return false;
            } else {
                return true;
            }
        }
    }

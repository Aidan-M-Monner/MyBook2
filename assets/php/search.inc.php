<?php
    session_start();
    include("classes/classes.php");

    // ------- Check Login ------- //
    $login = new Login();
    $user_data = $login->check_login($_SESSION['mybook_user_id']);

    if(isset($_GET['find'])) {
        $find = addslashes($_GET['find']);
        $sql = "select * from users where first_name like '%$find%' || last_name like '%$find%' limit 30";
        $DB = new Database();
        $results = $DB->read($sql);
    }

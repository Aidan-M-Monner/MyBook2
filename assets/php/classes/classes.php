<?php 
    include("connect.php");
    include("functions.php");
    include("image.class.php");
    include("login.class.php");
    include("messages.class.php");
    include("post.class.php");
    include("profile.class.php");
    include("settings.class.php");
    include("signup.class.php");
    include("time.php");
    include("user.class.php");

    if(!defined("ROOT")) {
        $root = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];
        $root = trim(str_replace("router.php", "", $root), "/");
        define("ROOT", $root . "/");
        $URL = split_url_array();
    }
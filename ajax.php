<?php 
    include("assets/php/classes/classes.php");
    
    $data = file_get_contents("php://input");

    if($data != "") {
        $data = json_decode($data);
    }
    
    if(isset($data->action) && $data->action == "like_post") {
        include "assets/ajax/like.ajax.php";
    }
<?php 
    session_start();
    include("classes/classes.php");

    // ------- Saved Login Variables ------- //
    $email = "";
    $password = "";

    // ------- Extracting Form Information ------- //
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $login = new Login();
        $result = $login->evaluate($_POST);
        
        if($result != "") {
            echo "<div style='background-color: grey; color: #FFF; font-size: 12px; text-align: center;'>";
            echo "<br> The following errors occured: <hr>";
            echo $result;
            echo "<br></div>";
        } else {
            header("Location: " . ROOT . "profile");
            die;
        }

        $email = $_POST['email'];
        $password = $_POST['password'];
    }



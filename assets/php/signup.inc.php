<?php 
    include("classes/classes.php");

    // ------- Saved Signup Variables ------- //
    $first_name = "";
    $last_name = "";
    $gender = "Gender";
    $email = "";

    // ------- Extracting Form Information ------- //
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $signup = new Signup();
        $result = $signup->evaluate($_POST);
        
        if($result != "") {
            echo "<div style='background-color: grey; color: #FFF; font-size: 12px; text-align: center;'>";
            echo "<br> The following errors occured: <hr>";
            echo $result;
            echo "<br></div>";
        } else {
            header("Location: login");
            die;
        }

        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $gender = $_POST['gender'];
        $email = $_POST['email'];
    }



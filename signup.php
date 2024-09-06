<?php include("assets/php/signup.inc.php"); ?>
<html>
    <head>
        <title>MyBook | Signup</title>
        <link rel="stylesheet" href="<?=ROOT?>assets/css/login.css" />
    </head>
    <body>
        <div id="top-bar">
            <div id="title">MyBook</div>
            <a href="<?=ROOT?>login" style="text-decoration: none;"><div id="signup">Login</div></a>
        </div>

        <div id="main">
            <div>Sign up to MyBook</div><br>
            <form method="post" action="signup.php">
                <input value="<?php echo $first_name; ?>" name="first_name" type="text" id="text" placeholder="First Name"><br><br>
                <input value="<?php echo $last_name; ?>" name="last_name" type="text" id="text" placeholder="Last Name"><br><br>
                <select name="gender" id="text">
                    <option selected><?php echo $gender; ?></option>
                    <option>Male</option>
                    <option>Female</option>
                </select><br><br>
                <input value="<?php echo $email ?>" name="email" type="text" id="text" placeholder="Email"><br><br>
                <input name="password" type="password" id="text" placeholder="Password"><br><br>
                <input name="password2" type="password" id="text" placeholder="Retype Password"><br><br>
                <input type="submit" id="submit" value="Signup"><br><br>
            </form>
        </div>
    </body>
</html>
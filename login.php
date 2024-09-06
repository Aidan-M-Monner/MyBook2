<?php include("assets/php/login.inc.php"); ?>
<html>
    <head>
        <title>MyBook | Login</title>
        <link rel="stylesheet" href="<?=ROOT?>assets/css/login.css" />
    </head>
    <body>
        <div id="top-bar">
            <div id="title">MyBook</div>
            <a href="<?=ROOT?>signup" style="text-decoration: none;"><div id="signup">Signup</div></a>
        </div>

        <div id="main">
            <form method="post">
                <div>Log in to MyBook</div><br>
                <input value="<?php echo $email; ?>" name="email" type="text" id="text" placeholder="Email"><br><br>
                <input value="<?php echo $password; ?>" name="password" type="password" id="text" placeholder="Password"><br><br>
                <input type="submit" id="submit" value="Login"><br><br>
            </form>
        </div>
    </body>
</html>
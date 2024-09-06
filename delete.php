<?php 
    include("assets/php/delete.inc.php");
?>
<html>
    <head>
        <title>MyBook | Delete</title>
        <link rel="stylesheet" href="<?=ROOT?>assets/css/profile.css" />
    </head>
    <body>
        <!-- Top Bar -->
        <?php include("header.php"); ?>

        <!-- Cover Area -->
        <div id="cover">

            <!-- Main Content -->
            <div id="content-area">

                <!-- Posts Area -->
                <div id="post-area">
                    <div id="create">
                        <form method="post">
                                <?php 
                                    if($ERROR != "") {
                                        echo $ERROR;
                                    } else {
                                        echo "Are you sure you want to delete this post?<br>";
                                        $user = new User();
                                        $ROW_USER = $user->get_user($ROW['user_id']);
                                        include("post_delete.php"); 
                                        echo "<input name='post_id' type='hidden' value='$ROW[post_id]'>";
                                        echo "<input id='post-button' type='submit' value='Delete'>";
                                    }
                                ?>
                            <br>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </body>
</html>
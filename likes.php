<?php 
    include("assets/php/likes.inc.php");
?>
<html>
    <head>
        <title>MyBook | Likes</title>
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
                        <?php 
                            $User = new User();
                            if(is_array($likes)) {
                                foreach($likes as $row) {
                                    $FRIEND_ROW = $User->get_user($row['user_id']);
                                    include("friend.php");
                                }
                            }
                        ?>
                        <br style="clear: both;">
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
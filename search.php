<?php 
    include("assets/php/search.inc.php");
?>
<html>
    <head>
        <title>MyBook | Search</title>
        <link rel="stylesheet" href="assets/css/profile.css" />
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
                            if(is_array($results)) {
                                foreach($results as $row) {
                                    $FRIEND_ROW = $User->get_user($row['user_id']);
                                    include("friend.php");
                                }
                            } else {
                                echo "no results were found.";
                            }
                        ?>
                        <br style="clear: both;">
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
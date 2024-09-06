<?php include("assets/php/change_img.inc.php"); ?>
<html>
    <head>
        <title>MyBook | Profile</title>
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
                    <form method="post" enctype="multipart/form-data">
                        <div id="create">
                            <input type="file" name="file">
                            <input id="change-button" type="submit" value="Change">
                            <br><br><br>
                            <div style='text-align: center;'>
                                <?php
                                    $change = "profile";
                                    if(isset($_GET['change']) && $_GET['change'] == "cover") {
                                        $change = "cover";
                                        echo "<img src='$user_data[cover_img]' style='max-width: 500px;'>";
                                    } else {
                                        echo "<img src='$user_data[profile_img]' style='max-width: 500px;'>";
                                    }
                                ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
<?php 
    include("assets/php/single_post.inc.php");
?>
<html>
    <head>
        <title>MyBook | Post</title>
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
                            $image_class = new Image();

                            if(is_array($ROW)) {
                                echo "<img src='" . ROOT . "$ROW[image]' style='width: 100%;' />";
                            }
                        ?>
                        <br style="clear: both;">
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
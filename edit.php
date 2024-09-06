<?php 
    include("assets/php/edit.inc.php");
?>
<html>
    <head>
        <title>MyBook | Edit</title>
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
                        <form method="post" enctype="multipart/form-data">
                                <?php 
                                    if($ERROR != "") {
                                        echo $ERROR;
                                    } else {
                                        echo "Edit Post<br>";
                                        echo "<textarea name='post' placeholder='What is on your mind?'>" . $ROW['post'] . "</textarea>";
                                        if(file_exists($ROW['image'])) {
                                            $image_class = new Image();
                                            $post_image = $image_class->get_thumb_post($ROW['image']);
                                            echo "<img src='" . ROOT . $post_image . "' style='width:80%;' />";
                                        }   
                                        echo "<input type='file' name='file'><br>";
                                        echo "<input name='post_id' type='hidden' value='$ROW[post_id]'>";
                                        echo "<input id='post-button' type='submit' value='Save'><br>";
                                    }
                                ?>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </body>
</html>
<?php 
    include("assets/php/single_post.inc.php");
?>
<html>
    <head>
        <title>MyBook | Post</title>
        <link rel="stylesheet" href="<?php echo ROOT; ?>assets/css/profile.css" />
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
                                $ROW_USER = $User->get_user($ROW['user_id']);
                                if($ROW['parent'] == 0) {
                                    include("post.php");
                                } else {
                                    $COMMENT = $ROW;
                                    $COMMENT_USER = $ROW_USER;
                                    include("comment.php");
                                }
                            }
                        ?>
                        <?php if($ROW['parent'] == 0): ?>
                            <br style="clear: both;">
                            <div id="create">
                                <form method="post" enctype="multipart/form-data">
                                    <textarea name="post" placeholder="Post a comment"></textarea>
                                    <input type="hidden" name="parent" value="<?php echo $ROW['post_id']; ?>">
                                    <input type="file" name="file">
                                    <input id="post-button" type="submit" value="Post">
                                    <br><br>
                                </form>
                            </div>
                            <br><br>
                        <?php else: ?>
                            <a href="<?=ROOT?>single_post/<?php echo $ROW['parent']; ?>">
                                <input type='button' value='Back to main post'>
                            </a>
                        <?php endif; ?>
                        <?php if($ROW['parent'] == 0): ?>
                            <?php
                                $comments = $Post->get_comments($ROW['post_id']);
                                if(is_array($comments)) {
                                    foreach($comments as $COMMENT) {
                                        $COMMENT_USER = $User->get_user($COMMENT['user_id']);
                                        include("comment.php");
                                    }
                                }
                            
                                // ------- Get Current Link ------- //
                                $pg = pagination_link();
                            ?>
                            <a href="<?php echo $pg['next_page']; ?>">
                                <input id="post_button" type="button" value="Next Page" style="background-color: #405d9b; border: none; color: #FFF; cursor: pointer; float: right; height: 25px; width: 150px;">
                            </a>
                            <a href="<?php echo $pg['prev_page']; ?>">
                                <input id="post_button" type="button" value="Prev Page" style="background-color: #405d9b; border: none; color: #FFF; cursor: pointer; float: left; height: 25px; width: 150px;">
                            </a>
                            <br style="clear: both;">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
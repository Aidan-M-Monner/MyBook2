<?php include("assets/php/timeline.inc.php"); ?>
<html>
    <head>
        <title>MyBook | Timeline</title>
        <link rel="stylesheet" href="assets/css/profile.css" />
    </head>
    <body>
        <!-- Top Bar -->
        <?php include("header.php"); ?>

        <!-- Cover Area -->
        <div id="cover">

            <!-- Main Content -->
            <div id="content-area">
                <!-- Friends Area -->
                <div id="user-area">
                    <div id="user-bar">
                        <img id="user-img" src="<?php echo $profile_img; ?>" /><br>
                        <span id="user-name"><?php echo $user_name; ?></span>
                    </div>
                </div>

                <!-- Posts Area -->
                <div id="post-area">
                <div id="create">
                    <form method="post" enctype="multipart/form-data">
                        <textarea name="post" placeholder="What is on your mind?"></textarea>
                        <input type="file" name="file">
                        <input id="post-button" type="submit" value="Post">
                        <br><br>
                    </form>
                </div>

                <div id="posts-area">
                    <?php 
                        // ------- Following Class Variables ------- //
                        $DB = new database();
                        $user = new User();
                        
                        // ------- Following Pagination Variables ------- //
                        $page_number = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                        $page_number = ($page_number < 1) ? 1 : $page_number;
                        $limit = 3;
                        $offset = ($page_number - 1) * $limit;

                        // ------- Grab User's Following ------- //
                        $followers = $user->get_following($_SESSION['mybook_user_id'], "user");
                        
                        // ------- Grab Individual Following ------- //
                        $follower_ids = false;
                        if(is_array($followers)) {
                            $follower_ids = array_column($followers, "user_id");
                            $follower_ids = implode("', '", $follower_ids);
                        }

                        // ------- Grab Following's Posts ------- //
                        if($follower_ids) {
                            $user_id = $_SESSION['mybook_user_id'];
                            $sql = "select * from posts where parent = 0 and (user_id = '$user_id' or user_id in('" . $follower_ids . "')) order by id desc limit $limit offset $offset";
                            $posts = $DB->read($sql);
                        } else {
                            $user_id = $_SESSION['mybook_user_id'];
                            $sql = "select * from posts where user_id = '$user_id' order by id desc limit 30";
                            $posts = $DB->read($sql);
                        }

                        // ------- Display Following's Posts ------- //
                        if(isset($posts) && $posts) {
                            foreach($posts as $ROW) {
                                $ROW_USER = $user->get_user($ROW['user_id']);
                                include("post.php");
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
                </div>
            </div>
        </div>
    </body>
</html>
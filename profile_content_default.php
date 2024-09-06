<div id="content-area">
    <!-- Friends Area -->
    <div id="friend-area">
        <div id="friends-bar">
            <span id="friends-title">Following</span><br>
            <?php 
                if($friends) {
                    foreach($friends as $friend) {
                        $FRIEND_ROW = $user->get_user($friend['user_id']);
                        include("friend.php");
                    }
                } 
            ?>
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
                if($posts) {
                    foreach($posts as $ROW) {
                        $user = new User();
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
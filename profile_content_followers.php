<div id="post-area" style="background-color: #FFF;">
    <div style="background-color: #FFF; padding: 20px;">
        <?php 
            $image_class = new Image();
            $post_class = new Post();
            $user_class = new User();
            $followers = $post_class->get_likes($profile_data['user_id'], "user");
            if(is_array($followers)) {
                foreach($followers as $follower) {
                    $FRIEND_ROW = $user_class->get_user($follower['user_id']);
                    include("friend.php");
                }
            } else {
                echo "No followers were found!";
            }
        ?>
        <br style="clear: both;">
    </div>
</div>
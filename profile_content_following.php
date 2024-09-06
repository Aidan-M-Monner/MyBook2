<div id="post-area" style="background-color: #FFF;">
    <div style="background-color: #FFF; padding: 20px;">
        <?php 
            $image_class = new Image();
            $user_class = new User();
            $following = $user_class->get_following($profile_data['user_id'], "user");
            if(is_array($following)) {
                foreach($following as $follower) {
                    $FRIEND_ROW = $user_class->get_user($follower['user_id']);
                    include("friend.php");
                }
            } else {
                echo "This user is not following anyone.";
            }
        ?>
        <br style="clear: both;">
    </div>
</div>
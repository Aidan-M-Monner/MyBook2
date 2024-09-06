<?php include("assets/php/comment.inc.php"); ?>
<div id="post">
    <div id="post-profile">
        <img src="<?php echo ROOT . $user_post_img; ?>" />
    </div>
    <div id="post-text">
        <div id="post-user">
            <?php 
                echo "<a href='" . ROOT . "profile/$COMMENT[user_id]'>" . $post_name . "</a>";
                if($COMMENT['profile_img']) {
                    echo "<span style='font-weight: normal; color: #aaa;'> updated $pronoun profile image</span>";
                } else if($COMMENT['cover_img']) {
                    echo "<span style='font-weight: normal; color: #aaa;'> updated $pronoun cover image</span>";
                }
            ?>
        </div>
        <?php echo $post_text; ?>
        <?php 
            if(file_exists($post_image)) { 
                $post_image = $image->get_thumb_post($post_image);
                echo "<br><br><img src='<?=ROOT?>$post_image' style='width: 100%;'/>"; 
            }
        ?>
        <a id='info_<?php echo $post_id; ?>' href='<?=ROOT?>likes/post/<?=$post_id?>' style='text-decoration: none'><?php echo $likes_message; ?></a>
        <hr>
        <a onclick="like_post(event, 'comment')" href="like/post/<?php echo $post_id; ?>"><?php echo $likes; ?></a> .  
        <span id="date"><?php echo $post_date; ?></span>

        <?php
            if($COMMENT['has_image']) {
                echo ". <a href='" . ROOT . "image_view/$COMMENT[post_id]'>View Full Image</a>";
            }
        ?>

        <span style="color: #999; float: right;">
            <?php 
                $post = new Post();
                if($post->post_owner($post_id, $_SESSION['mybook_user_id'])) {
                    echo "<a href='" . ROOT . "edit/$post_id' style='text-decoration: none;'>Edit</a> . ";
                }

                if(i_own_content($COMMENT)) {
                    echo "<a href='" . ROOT . "delete/$post_id' style='text-decoration: none;'>Delete</a>";
                }
            ?>
        </span>
    </div>
</div>
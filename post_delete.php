<?php include("assets/php/post.inc.php"); ?>
<div id="post">
    <div id="post-profile">
        <img src="<?php echo ROOT . $user_post_img; ?>" />
    </div>
    <div id="post-text">
        <div id="post-user">
            <?php 
                echo $post_name;
                if($ROW['profile_img']) {
                    echo "<span style='font-weight: normal; color: #aaa;'> updated $pronoun profile image</span>";
                } else if($ROW['cover_img']) {
                    echo "<span style='font-weight: normal; color: #aaa;'> updated $pronoun cover image</span>";
                }
            ?>
        </div>
        <?php echo $post_text; ?>
        <?php 
            if(file_exists($post_image)) { 
                $post_image = $image->get_thumb_post($post_image);
                echo "<br><br><img src='" . ROOT . $post_image . "' style='width: 100%;'/>"; 
            }
        ?>
    </div>
</div>
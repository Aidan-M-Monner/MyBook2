<?php include("assets/php/message.inc.php"); ?>
<div id="message_left">
    <div id="post-profile">
        <img src="<?php echo ROOT . $user_post_img; ?>" />
    </div>
    <div id="post-text">
        <div id="post-user">
            <?php 
                echo "<a href='" . ROOT . "profile/$MESSAGE[sender]'>" . $message_name . "</a>";
            ?>
        </div>
        <?php echo $message_text; ?>
        <?php 
            if(file_exists($message_image)) { 
                $message_image = $image->get_thumb_post($message_image);
                echo "<br><br><img src='<?=ROOT?>$message_image' style='width: 100%;'/>"; 
            }
        ?>
        <hr> 
        <span id="date"><?php echo $message_date; ?></span>

        <?php
            if(file_exists($MESSAGE['file'])) {
                echo ". <a href='" . ROOT . "image_view/$MESSAGE[msg_id]'>View Full Image</a>";
            }
        ?>

        <span style="color: #999; float: right;">
            <?php 
                $post = new Post();
                if($post->post_owner($message_id, $_SESSION['mybook_user_id'])) {
                    echo "<a href='" . ROOT . "edit/$message_id' style='text-decoration: none;'>Edit</a> . ";
                }

                if(i_own_content($MESSAGE)) {
                    echo "<a href='" . ROOT . "delete/$message_id' style='text-decoration: none;'>Delete</a>";
                }
            ?>
        </span>
    </div>
</div>
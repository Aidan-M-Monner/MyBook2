<?php include("assets/php/friend.inc.php"); ?>
<div id="friend">
    <a href="<?=ROOT?>profile/<?php echo $friend_id; ?>" style="text-decoration: none;">
        <img id="friend-img" src="<?php echo ROOT . $friend_img; ?>" /><br>
        <?php echo $friend_name; ?>
        <br>
        <span style="color:grey; font-size: 10px;"><?php echo $friend_online; ?></span>
    </a>
</div>
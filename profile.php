<?php 
    include("assets/php/profile.inc.php");
?>
<html>
    <head>
        <title>MyBook | Profile</title>
        <link rel="stylesheet" href="<?php echo ROOT; ?>assets/css/profile.css" />
    </head>
    <body>
        <!-- Top Bar -->
        <?php include("header.php"); ?>

        <!-- Change Profile Img --> 
        <div id="change_profile_image" style="background-color: #000000aa; display: none; height: 100%; position: absolute; width: 100%;">
            <div id="post-area" style="max-width: 600px;">
                <form method="post" action="<?=ROOT?>profile/profile" enctype="multipart/form-data">
                    <div id="create">
                        <input type="file" name="file">
                        <input id="change-button" type="submit" value="Change" style="width: 120px;">
                        <br><br><br>
                        <div style='text-align: center;'>
                            <?php
                                echo "<img src='" . ROOT . "$user_data[profile_img]' style='max-width: 500px;'>";
                            ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Change Cover Img --> 
        <div id="change_cover_image" style="background-color: #000000aa; display: none; height: 100%; position: absolute; width: 100%;">
            <div id="post-area" style="max-width: 600px;">
                <form method="post" action="<?=ROOT?>profile/cover" enctype="multipart/form-data">
                    <div id="create">
                        <input type="file" name="file">
                        <input id="change-button" type="submit" value="Change" style="width: 120px;">
                        <br><br><br>
                        <div style='text-align: center;'>
                            <?php
                                echo "<img src='" . ROOT . "$user_data[cover_img]' style='max-width: 500px;'>";
                            ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Cover Area -->
        <div id="cover">
            <div id="cover-background">
                <img src="<?php echo ROOT . $cover_img; ?>" id="background-img"/>
                <img src="<?php echo ROOT . $profile_img; ?>" id="profile-img"/>
                <div id="name"><a href="<?=ROOT?>profile/<?php echo $profile_data['user_id']; ?>" style="text-decoration: none;"><?php echo $user_name; ?></a></div>
                <span><?php echo "@" . $profile_data['tag_name']; ?></span>
                <div><?php echo $myFollowers; ?></div>
                <?php if($user_data != null && $user_data['user_id'] != $profile_data['user_id']):?>
                    <a href="<?=ROOT?>like/user/<?php echo $profile_data['user_id']; ?>"><input id="post-button" type="submit" value="<?php echo $i_follow; ?>" id="like-button" style="margin-right: 10%; width: 10%"></a>
                    <a href="<?=ROOT?>messages/new/<?=$profile_data['user_id'];?>"><input id="post-button" type="submit" value="Message" id="like-button" style="background-color: #10A170; margin-right: 10%; width: 10%"></a>
                <?php else:?>
                    <a href="<?=ROOT?>messages"><input id="post-button" type="submit" value="Messages" id="like-button" style="background-color: #10A170; margin-right: 10%; width: 10%"></a>
                <?php endif;?>
                
                <br><hr>
                <a href="<?=ROOT?>home"><div id="menu-button">Timeline</div></a>
                <a href="<?=ROOT?>profile/<?php echo $profile_data['user_id']; ?>/about"><div id="menu-button">About</div></a>
                <a href="<?=ROOT?>profile/<?php echo $profile_data['user_id']; ?>/followers"><div id="menu-button">Followers</div></a>
                <a href="<?=ROOT?>profile/<?php echo $profile_data['user_id']; ?>/following"><div id="menu-button">Following</div></a>
                <a href="<?=ROOT?>profile/<?php echo $profile_data['user_id']; ?>/photos"><div id="menu-button">Photos</div></a>

                <?php 
                    if(i_own_content($profile_data)) {
                        echo '<a href="' . ROOT . 'profile/' . $profile_data['user_id'] . '/settings"><div id="menu-button">Settings</div></a>';
                    }
                ?>
            </div>

            <?php
                if(!isset($URL[2])) {
                    $URL[2] = "default";
                }

                if($URL[2] == "default") {
                    include("profile_content_default.php");
                } else if($URL[2] == "about") {
                    include("profile_content_about.php");
                } else if($URL[2] == "followers") {
                    include("profile_content_followers.php");
                } else if($URL[2] == "following") {
                    include("profile_content_following.php");
                } else if($URL[2] == "photos") {
                    include("profile_content_photos.php");
                } else if($URL[2] == "settings") {
                    include("profile_content_settings.php");
                }
            ?>
        </div>
    </body>
</html>
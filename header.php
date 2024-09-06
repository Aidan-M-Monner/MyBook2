<?php
    $image = new Image();
    $corner_image = "assets/img/user_male.png";
    if($_SESSION['mybook_user_id'] != 0) {
        if(file_exists($user_data['profile_img'])) {
            $corner_image = $image->get_thumb_profile($user_data['profile_img']);
        } else if(file_exists($user_data['profile_img'] . "_profile_thumb.jpg")) {
            $corner_image = $user_data['profile_img'] . "_profile_thumb.jpg";
        } else if($user_data['gender'] == "Male") {
            $corner_image = "assets/img/user_male.png";
        } else if($user_data['gender'] == "Female") {
            $corner_image = "assets/img/user_female.jpg";
        }
    }
?>

<div id="top-bar">
    <form method="get" action="<?=ROOT?>search">
        <div id="title">
            <a href="<?=ROOT?>home" id="logo">MyBook</a> &nbsp; &nbsp;
            <input type="text" id="search-box" name="find" placeholder="Search for people" />
            <div id="main-profile">
                <?php if(isset($user_data)): ?>
                    <a href="<?=ROOT?>notifications"><span style="display: inline-block; position: relative;">
                        <img src="<?=ROOT?>assets/img/notif.svg" style="float: right; margin-bottom: 10px; width: 30px;">
                        <?php 
                            $notif = check_notifications();
                        ?>

                        <?php if($notif > 0): ?>
                            <div style="background-color: red; border-radius: 50%; color: white; font-size: 12px; height: 13px; padding: 2px; position: absolute; text-align: center; width: 13px;"><?= $notif ?></div>
                        <?php endif; ?>
                    </span></a>
                    <a href="<?=ROOT?>profile/<?=$user_data['user_id']?>"><img src="<?php echo ROOT . $corner_image; ?>" id="profile-pic"></a>
                    <a href="<?=ROOT?>logout"><span id="logout">Logout</span></a>
                <?php else: ?>
                    <span id="profile-content">
                        <a href="<?=ROOT?>login"><span id="logout">Login</span></a>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>
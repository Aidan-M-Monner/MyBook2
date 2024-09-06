<?php 
    include("assets/php/notifications.inc.php");
?>
<html>
    <head>
        <title>MyBook | Notifications</title>
        <link rel="stylesheet" href="<?=ROOT?>assets/css/profile.css" />
    </head>
    <body>
        <!-- Top Bar -->
        <?php include("header.php"); ?>

        <!-- Cover Area -->
        <div id="cover">

            <!-- Main Content -->
            <div id="content-area">

                <!-- Posts Area -->
                <div id="post-area">
                    <div id="create">
                        <?php
                            $DB = new Database();
                            $id = esc($_SESSION['mybook_user_id']);
                            $followed = array();

                            // Check the content I follow
                            $sql = "select * from followed_content where disabled = 0 && user_id = '$id' limit 100";
                            $i_follow = $DB->read($sql);
                            if(is_array($i_follow)) {
                                $followed = array_column($i_follow, "content_id");
                            }

                            if(count($followed) > 0) {
                                $str = "'" . implode("','", $followed) . "'";
                                $query = "select * from notifications where (user_id != '$id' && content_owner = '$id') || (content_id in ($str)) order by id desc limit 30";
                            } else {
                                $query = "select * from notifications where user_id != '$id' && content_owner = '$id' order by id desc limit 30";
                            }
                            $data = $DB->read($query);
                        ?>

                        <?php if(is_array($data)): ?>
                            <?php foreach($data as $notif_row): ?>
                                <?php include("single_notification.php"); ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            No Notifications were found.
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </body>
</html>
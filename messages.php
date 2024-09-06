<?php 
    include("assets/php/messages.inc.php");
?>
<html>
    <head>
        <title>MyBook | Messages</title>
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
                        <form method="post" enctype="multipart/form-data">
                                <?php 
                                    if($ERROR != "") {
                                        echo $ERROR;
                                    } else {
                                        if(isset($URL[1]) && $URL[1] == "read") {
                                            echo "Chatting with: <br><br>";
                                            if(isset($URL[2]) && is_numeric($URL[2])) {
                                                $data = $msg_class->read($URL[2]);
                                                
                                                // Grab contact
                                                $user = new User();
                                                $FRIEND_ROW = $user->get_user($URL[2]);

                                                echo "<div style='display: inline-block; width: 60%;'>";
                                                    include "friend.php";
                                                echo "</div>";

                                                // Message area
                                                echo '<div>';
                                                    foreach($data as $msg_row) {
                                                        $MESSAGE = $msg_row;
                                                        $MESSAGE_USER = $user_class->get_user($msg_row['sender']);
                                                        include "message_left.php";
                                                    }
                                                echo '</div>';
                                                
                                                echo '
                                                    <div id="create">
                                                        <textarea name="message" placeholder="Write your message here..."></textarea>
                                                        <input type="file" name="file">
                                                        <input id="post-button" type="submit" value="Send">
                                                        <br><br>
                                                    </div>
                                                ';
                                            } else {
                                                echo "User could not be found.";
                                            }
                                        } else if(isset($URL[1]) && $URL[1] == "new") {
                                            echo "Start New Message with: <br><br>";
                                            if(isset($URL[2]) && is_numeric($URL[2])) {
                                                
                                                // Grab contact
                                                $user = new User();
                                                $FRIEND_ROW = $user->get_user($URL[2]);

                                                echo "<div style='display: inline-block; width: 60%;'>";
                                                    include "friend.php";
                                                echo "</div>";

                                                // Message area
                                                echo '
                                                    <div id="create">
                                                        <textarea name="message" placeholder="Write your message here..."></textarea>
                                                        <input type="file" name="file">
                                                        <input id="post-button" type="submit" value="Send">
                                                        <br><br>
                                                    </div>
                                                ';
                                            } else {
                                                echo "User could not be found.";
                                            }
                                        } else {
                                            echo "Messages<br><br>";
                                            $user = new User();
                                            $ROW_USER = $user->get_user($ROW['user_id']);
                                            include("message.php"); 
                                            echo "<input name='post_id' type='hidden' value='$ROW[post_id]'>";
                                            echo "<input id='post-button' type='submit' value='Delete'>";
                                        }
                                    }
                                ?>
                            <br>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </body>
</html>
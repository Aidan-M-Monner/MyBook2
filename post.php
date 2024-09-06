<?php include("assets/php/post.inc.php"); ?>
<div id="post">
    <div id="post-profile">
        <img src="<?php echo ROOT . $user_post_img; ?>" />
    </div>
    <div id="post-text">
        <div id="post-user">
            <?php 
                echo "<a href='" . ROOT . "profile.php/$ROW[user_id]'>" . $post_name . "</a>";
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
                echo "<a href='" . ROOT . "single_post/" . $ROW['post_id'] . "'>";
                echo "<br><br><img src='" . ROOT . "$post_image' style='width: 100%;'/>"; 
                echo "</a>";
            }
        ?>

        <a id='info_<?php echo $post_id; ?>' href='<?=ROOT?>likes/post/<?=$post_id?>' style='text-decoration: none'><?php echo $likes_message; ?></a>

        <hr>
        <a onclick="like_post(event, 'post')" href="like/post/<?php echo $post_id; ?>"><?php echo $likes; ?></a> . 
        <a href="<?=ROOT?>single_post/<?php echo $ROW['post_id'] ?>"><?php echo $comments; ?></a> . 
        <span id="date"><?php echo $post_date; ?></span>

        <?php
            if($ROW['has_image']) {
                echo ". <a href='" . ROOT . "image_view/$ROW[post_id]'>View Full Image</a>";
            }
        ?>

        <span style="color: #999; float: right;">
            <?php 
                $post = new Post();
                if($post->post_owner($post_id, $_SESSION['mybook_user_id'])) {
                    echo "<a href='" . ROOT . "edit/$post_id' style='text-decoration: none;'>Edit</a> . ";
                    echo "<a href='" . ROOT . "delete/$post_id' style='text-decoration: none;'>Delete</a>";
                }
            ?>
        </span>
    </div>
</div>

<script type="text/javascript">
    function ajax_send(data, element) {
        var ajax = new XMLHttpRequest();

        // Listen for response
        ajax.addEventListener('readystatechange', function(){
            if(ajax.readyState == 4 && ajax.status == 200) { // 4 is ready to go/there is a response, 200 is found/everything went well.
                response(ajax.responseText, element);
            }
        });

        data = JSON.stringify(data);

        ajax.open("post", "<?=ROOT?>ajax.php", true);
        ajax.send(data);
    }

    function response(result, element) {
        if(result != "") {
            var obj = JSON.parse(result);
            if(typeof obj.action != 'undefined') {
                if(obj.action == 'like_post') {
                    var likes = "";
                    if(typeof obj.likes != 'undefined') {
                        likes = (parseInt(obj.likes) > 0) ? "Likes(" + obj.likes + ")" : "Like";
                        element.innerHTML = likes;
                    }

                    if(typeof obj.info != 'undefined') {
                        var info_element = document.getElementById(obj.id);
                        info_element.innerHTML = obj.info;
                    }
                }
            }
        }
    }

    function like_post(e, type) {
        // Prevent refresh
        e.preventDefault();

        var link = e.target.href;
        var data = {};
        data.link = link;
        data.action = "like_post";
        data.type = type;
        ajax_send(data, e.target);
    }
</script>
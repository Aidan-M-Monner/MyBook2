<div id="post-area" style="background-color: #FFF; text-align: center">
    <div style="padding: 20px;">
        <?php 
            $DB = new Database();
            $sql = "select image, post_id from posts where has_image = 1 && user_id = $profile_data[user_id] order by id desc limit 30";
            $images = $DB->read($sql);

            $image_class = new Image();
            if(is_array($images)) {
                foreach($images as $image_row) {
                    echo "<a href='" . ROOT . "single_post/$image_row[post_id]'><img src='" . ROOT . $image_class->get_thumb_post($image_row['image']) ."' style='margin: 10px; width: 100px;'></a>";
                }
            } else {
                echo "No images were found!";
            }
        ?>
    </div>
</div>
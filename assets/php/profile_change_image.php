<?php 
    if(isset($URL[1]) && ($URL[1] == "profile" || $URL[1] == "cover")) {
        if(isset($_FILES['file']['name']) && $_FILES['file']['name'] != "") {
            $allowed_types = ["image/jpeg"];
            if(in_array($_FILES['file']['type'], $allowed_types)) {
                $allowed_size = (1024 * 1024) * 3;
                if($_FILES['file']['size'] < $allowed_size) {
                    $image = new Image();

                    // --- Create Folder --- //
                    $folder = "uploads/" . $user_data['user_id'] . "/";
                    if(!file_exists($folder)) {
                        mkdir($folder, 0777, true);
                        file_put_contents($folder, "index.php");
                    }
                    $filename = $folder . $image->generate_filename(15) . ".jpg";
                    move_uploaded_file($_FILES['file']['tmp_name'], $filename);

                    // --- Crop Image --- //
                    $change = "profile";
                    if(isset($URL[1])) {
                        $change = $URL[1];
                    }

                    if($change == "cover") {
                        if(file_exists($user_data['cover_img'])) {
                            unlink($user_data['cover_img'] . "_cover_thumb.jpg");
                        }
                        $image->resize_image_jpeg($filename, $filename, 1500, 1500);
                    } else {
                        if(file_exists($user_data['profile_img'])) {
                            unlink($user_data['profile_img'] . "_profile_thumb.jpg");
                        }
                        $image->resize_image_jpeg($filename, $filename, 1500, 1500);
                    }

                    if(file_exists($filename)) {
                        $DB = new Database();
                        $user_id = $user_data['user_id'];

                        if($change == "cover") {
                            $query = "update users set cover_img = '$filename' where user_id = '$user_id' limit 1";
                            $_POST['cover_img'] = 1;
                        } else {
                            $query = "update users set profile_img = '$filename' where user_id = '$user_id' limit 1";
                            $_POST['profile_img'] = 1;
                        }

                        $DB->save($query);

                        // --- Create Post --- //
                        $post = new Post();
                        $result = $post->create_post($user_id, $_POST, $filename);

                        header(("Location: " . ROOT . "profile"));
                        die;
                    }
                } else {
                    echo "<div style='background-color: grey; color: #FFF; font-size: 12px; text-align: center;'>";
                    echo "<br> The following errors occured: <hr>";
                    echo "Only images of size 3MB are allowed.";
                    echo "<br></div>";
                }
            } else {
                echo "<div style='background-color: grey; color: #FFF; font-size: 12px; text-align: center;'>";
                echo "<br> The following errors occured: <hr>";
                echo "Only images of Jpeg type are allowed.";
                echo "<br></div>";
            }
        } else {
            echo "<div style='background-color: grey; color: #FFF; font-size: 12px; text-align: center;'>";
            echo "<br> The following errors occured: <hr>";
            echo "Please add a valid image.";
            echo "<br></div>";
        }
    }
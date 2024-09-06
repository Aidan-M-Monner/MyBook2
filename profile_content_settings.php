<style>
#text-box {
    border: solid thin grey;
    border-radius: 5px;
    font-size: 14px;
    height: 25px;
    margin: 10px; 
    padding: 4px;
    width: 100%;
}
</style>

<div id="post-area" style="background-color: #FFF; text-align: center">
    <div style="display: inline-block; max-width: 350px; padding: 20px;">

        <form method="post" enctype="multipart/form-data">
            <?php 
                $settings_class = new Settings();
                $settings = $settings_class->get_settings($_SESSION['mybook_user_id']);

                if(is_array($settings)) {
                    echo "<input type='text' id='text-box' name='first_name' value='" . htmlspecialchars($settings['first_name']) . "' placeholder='First Name'/>";
                    echo "<input type='text' id='text-box' name='last_name' value='" . htmlspecialchars($settings['last_name']) . "' placeholder='Last Name'/>";

                    echo "<select id='text-box' name='email' style='height: 30px;'>
                        <option>" . htmlspecialchars($settings['gender']) . "</option>
                        <option>Male</option>
                        <option>Female</option>
                    </select>";

                    echo "<input type='text' id='text-box' name='email' value='" . htmlspecialchars($settings['email']) . "' placeholder='Email'/>";
                    echo "<input type='password' id='text-box' name='password' value='" . htmlspecialchars($settings['password']) . "' placeholder='password'/>";
                    echo "<input type='password' id='text-box' name='password2' value='" . htmlspecialchars($settings['password']) . "' placeholder='password'/>";

                    echo "About Me: <br>";
                    echo "<textarea id='text-box' style='height: 200px;' name='about'>" . htmlspecialchars($settings['about']) . "</textarea>";

                    echo "<input id='post-button' type='submit' value='Save'>";
                }
            ?>
        </form>
    </div>
    <br style="clear: both;">
    <a onclick="show_change_profile_image(event)" href="<?=ROOT?>change_img/profile" class="class-d">Change Image</a> |
    <a onclick="show_change_cover_image(event)" href="<?=ROOT?>change_img/cover" class="class-d">Change Cover</a>
</div>

<script type="text/javascript">
    function show_change_profile_image(event) {
        event.preventDefault();
        var profile_image = document.getElementById("change_profile_image");
        profile_image.style.display = "block";
    }

    function hide_change_profile_image() {
        var profile_image = document.getElementById("change_profile_image");
        profile_image.style.display = "none";
    }

    function show_change_cover_image(event) {
        event.preventDefault();
        var cover_image = document.getElementById("change_cover_image");
        cover_image.style.display = "block";
    }

    function hide_change_cover_image() {
        var cover_image = document.getElementById("change_cover_image");
        cover_image.style.display = "none";
    }

    window.onkeydown = function(key) {
        if(key.keyCode == 27) {
            // esc key pressed //
            hide_change_profile_image();
            hide_change_cover_image();
        }
    }
</script>
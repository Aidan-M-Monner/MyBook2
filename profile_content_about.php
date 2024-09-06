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
        <?php 
            $settings_class = new Settings();
            $settings = $settings_class->get_settings($_SESSION['mybook_user_id']);

            if(is_array($settings)) {
                echo "About Me: <br>";
                echo "<div id='text-box' style='height: 200px; width: 400px;' name='about'>" . htmlspecialchars($settings['about']) . "</div>";
            }
        ?>
    </div>
    <br style="clear: both;">
</div>
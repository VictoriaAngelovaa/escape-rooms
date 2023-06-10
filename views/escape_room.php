<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h2>Help</h2> <textarea cols=100 rows=12>//WHEN FINISH -> redirect to         
            header("Location: <?php echo ($_GET["callback_url"] ?? "NA"); ?>");  
            die();

            ====================
            "extention": {
                "id": "1",
                "open_url": "http://localhost/edit_image.php?imgurl=$IMAGE_NAME|app=$GAMENAME|username=player|mode=edit",
                "back_url": "http://game-edu-thesis/save_image.asp?$image=BASE64-or-url",
                "icon": "icon.jpg",
                "title": "FIrst locker task"
            }

  

        </textarea>

    <?php
    class MysteryConfig
    {
        private $id = 1;

        private $url_base = "http://localhost/escape-rooms/views";
        //  private $url_integration = "/action.php?action=required_actions";
        //supported actions (play, explore, preview, return, timer), optional actions(play,score,pause,resume,terminate,restart,), 
        //supported features: lang_UI, lang_DATA, theme: logo, theme-name(light,dark), core color1, color2, color3
        //extra-features: show/hide-timer, show/hide avatar (or players?)
        private $url_action = "/escape_room.php";
        // private $open_url = "http://localhost/edit_image.php?imgurl=$IMAGE_NAME|app=$GAMENAME|username=player|mode=edit";
        private $lang_UI = "en_EN_WIN"; //en_US, en, bg, bg_BG, bg_BG_WIN, bg_BG_MAC -> IN TARGED
        private $lang_DATA = "bg"; //EN -> IN TARGET

        private $theme_name = "dark"; //light -> optional
        private $theme_logo = "logo1.jpg"; //or url -> optional

        private $user_from_system = "escape-rooms"; //SOURCE project name
        private $user_id = "9999"; //FN        -> if reqired // user-id -> in SOURCE-PROJECT
        private $user_email = "milenp@fmi.uni-sofia.bg"; // //if required -> user email in SOURCE PROJECT
        private $user_avatar = "http://localhost/escape-rooms/generated/level-logos/1.jpg"; //if required, може потребителя да си има аватар в новата система;

        private $app_source_id = "123"; //SOURCE app number
        private $app_secret = "secret"; //SOURCE secret - if required

        private $is_return = "FALSE";
        //returned - your app link
        private $callback_url = "http://localhost/escape-rooms/views/escape_room.php?userid=9999&lock_result=ABCD&action=FINISHED&is_return=TRUE";

        public function get_GO_link(
            $url_action = "/escape_room.php",
            $user_id = "9999",
            $lang_UI = "en",
            $data_UI = "bg",
            $theme = "dark",
            $action = "play",
        ) {

            $result =  $this->url_base .
                $this->url_action . "?";

            //    $params = "user_id,lang_UI,data_UI,theme,theme_color1=white,theme_color2=black";
            //    $list = preg_split ("/\,/", $params);
            $result .= "&action=$action" .
                "&user_id=" . htmlentities($user_id) .
                "&lang_UI=en" .
                "&lang_DATA=bg" .
                "&theme=dark" .
                 "&callback_url=" . urlencode($this->callback_url);

            return $result;
        }
        public function dump_params()
        {
            echo "<li>Your ACTION is: " .  ($_GET["action"] ?? "NA") . "</li>";
            echo "<li>Your UI language is: " .  ($_GET["lang_UI"] ?? "NA") . "</li>";
            echo "<li>Your THEME is: " .  ($_GET["theme"] ?? "NA") . "</li>";
            echo "<li>Your CALLBACK URL is: " . ($_GET["callback_url"] ?? "NA") . "</li>";
            echo "<li>Your IS_RETURN URL is: " . ($_GET["is_return"] ?? "false") . "</li>";
        }
    }
    ?>

    <?php

    $cfg = new MysteryConfig();
    echo "<h2>Entry params</h2>";
    $cfg->dump_params();
    echo "<hr> ..";
    //($action="PLAY",  $user_id = "9999"
    $link =  $cfg->get_GO_link();
    echo "<h2>Integration GO link</h2> GO link: " .
        "PLAY: <a href=" . $link  . "'>" .  $link . "</a>";
    ?>



    <?php
    echo "<h2>Integration RETURN link</h2> " .
        "RETURN: <a href=" . ($_GET["callback_url"] ?? "NA")  . "'>" .  ($_GET["callback_url"] ?? "NA") . "</a>";

    ?>


</body>

</html>
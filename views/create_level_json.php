<?php
session_start();
if (isset($_SESSION['username'])) {
    $user = $_SESSION['username'];
}
else {
    header('Location: ./login.php');
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Създай Ниво</title>
    <script src="../scripts/create_level_json.js" type="module" defer></script>
    <script src="../scripts/navigation.js" type="module" defer></script>
    <script type="text/javascript">
        var user='<?php echo $user;?>';
    </script>
    <link rel="stylesheet" href="./styles/common.css">
    <link rel="stylesheet" href="./styles/create_level.css">
    <link rel="stylesheet" href="./styles/create_level_json.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:300,400,500&display=swap" rel="stylesheet">
</head>

<body>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap');
  @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400&display=swap');
</style>
    <header>
        <div id="popup"></div>
        <nav>
            <h1>ESCAPE ROOMS</h1>
			<ul id="navbar">
				<li class="dropdown">
                    <a href="javascript:void(0)">СЪЗДАЙ ИГРА</a>
                    <ul class="nav-dropdown">
                        <li><a href="../views/create_game.php">ФОРМА</a></li>
                        <li><a href="../views/create_game_json.php">JSON</a></li>
                    </ul>
                </li>
				<li class="dropdown">
                    <a href="javascript:void(0)">СЪЗДАЙ НИВО</a>
                    <ul class="nav-dropdown">
                        <li><a href="../views/create_level.php">ФОРМА</a></li>
                        <li><a href="../views/create_level_json.php">JSON</a></li>
                    </ul>
                </li>
                <li><a href="../views/levels.php">НИВА</a></li>
                <li><a href="../views/games.php">ИГРИ</a></li>
			</ul>
            <div>
                <button class="account-button"><img src="./images/user-svgrepo-com.svg" width="31px"></button>
                <button class="exit-button"><img src="./images/exit_icon.svg" width="28px"></button>
            </div>
		</nav>
	</header>
    <main id="create-level-json">
        <h2>Стъпки за създаване на ниво с JSON конфигурация:</h2>
        <ol>
            <li>Изтегли и попълни шаблона.</li>
            <button class="generic-button" id="download-template-button" type="button"><span>ИЗТЕГЛИ ШАБЛОН</span></button>
            <li>Качи готовия JSON файл.</li>
            <input type="file" name="resource" id="resource-file" hidden/>
            <label id="label-resource" for="resource-file">Upload JSON configuration  *</label>
            <li>Създай ниво.</li>
            <button class="generic-button" id="upload-button" type="submit"><span>СЪЗДАЙ НИВО</span></button>
        </ol>
        
        
        
    </main>

</body>

</html>
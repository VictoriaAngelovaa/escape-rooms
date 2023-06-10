<?php
    session_start();
    if (isset($_SESSION['username'])) {
        $user = $_SESSION['username'];
    }
    else if(isset($_GET['user']) && $_GET['user'] === 'guest') {
        $user = "guest";
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
    <title>Ниво</title>
    <script src="../scripts/selected_level.js" type="module" defer></script>
    <script src="../scripts/navigation.js" type="module" defer></script>
    <script type="text/javascript">
        var user='<?php echo $user;?>';
    </script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="./styles/common.css">
    <link rel="stylesheet" href="./styles/games.css">
    <link rel="stylesheet" href="./styles/selected_level.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
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
    <main>
        <section id="timer-answer">
            <div class="timer">
                <span class="timer__part timer__part--hours">00</span>
                <span class="timer__part">:</span>
                <span class="timer__part timer__part--minutes">00</span>
                <span class="timer__part">:</span>
                <span class="timer__part timer__part--seconds">00</span>
                <button type="button" class="timer__btn timer__btn--control timer__btn--start">
                    <span class="material-icons">lock_clock</span>
                </button>
            </div>
        </section>
        <section id="level-info">
        </section>
    </main>
</body>

</html>
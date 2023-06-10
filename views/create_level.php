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
    <script src="../scripts/create_level.js" type="module" defer></script>
    <script src="../scripts/navigation.js" type="module" defer></script>
    <script type="text/javascript">
        var user='<?php echo $user;?>';
    </script>
    <link rel="stylesheet" href="./styles/games.css">
    <link rel="stylesheet" href="./styles/common.css">
    <link rel="stylesheet" href="./styles/create_level.css">
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
    <main id="create-level">
        <form id="create-level-form" enctype="application/json">
            <div id="logo-name-desc"> <!-- logo + ime + desc -->
                <div id="logo">
                    <input class="generic-input"type="file" name="logo-image" id="logo-file" hidden/>
                    <label for="logo-file">
                        <img id="logo-image" src="./images/picture_icon.svg">  
                        <span>Upload Logo  *</span>
                    </label>
                </div>

                <div id="name-desc">
                    <input class="generic-input" type="text" name="name" placeholder="Име  *" required/>
                    <textarea name="desc" rows="5" cols="50" placeholder="Описание"></textarea>
                </div>
            </div>

            <!-- lock + type + answer + attempts + points -->
            <div class="other-info">
                <input class="generic-input" type="text" name="type" list="level-type" placeholder="Вид  *" required/>
                <datalist id="level-type">
                    <option value="Online"></option>
                    <option value="Live"></option>
                </datalist>
                
                <input class="generic-input" type="text" name="category" list="category-type" placeholder="Категория  *" required/>
                <datalist id="category-type">
                    <option value="Puzzle"></option>
                    <option value="Horror"></option>
                    <option value="Survival"></option>
                    <option value="Creative"></option>
                    <option value="Mathematical"></option>
                </datalist>
                <input class="generic-input" type="text" name="lock" list="lock-type" placeholder="Ключалка  *" required/>
                <datalist id="lock-type">
                    <option value="3 Digit Lock"></option>
                    <option value="4 Digit Lock"></option>
                    <option value="Word"></option>
                    <option value="Direction"></option>
                </datalist>
            </div>
            <div class="other-info">
                <input class="generic-input" type="text" name="answer" placeholder="Отговор  *" required/>
                <input class="generic-input" type="number" name="attempts" placeholder="Възможни опити  *" required/>
                <input class="generic-input" type="number" name="points" placeholder="Точки  *" required/>
            </div>
            <div class="other-info">
                <input class="generic-input" type="number" name="duration" placeholder="Времетраене в мин *" required/>
                <input class="generic-input" type="text" name="open" placeholder="Open Url"/>
                <input class="generic-input" type="file" name="resource" id="resource-file" hidden/>
                <label id="label-resource" for="resource-file">Upload Resource  *</label>
            </div>
            <button class="generic-button" type="submit"><span>СЪЗДАЙ НИВО</span></button>
        </form>

  </main>

</body>

</html>
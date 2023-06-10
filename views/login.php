<?php 
    session_start();
    // TODO: go to somewhere else if has session
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <script src="../scripts/login.js" type="module" defer></script>
    <link rel="stylesheet" href="./styles/common.css">
    <link rel="stylesheet" href="./styles/login.css">
</head>

<body>
    <h1>ESCAPE ROOMS</h1>
    <div id="login-container">
        <form>
            <div id="error-message"></div>
            <input id='username-input' class='generic-input' type="text" name="username" placeholder="Потребителско име" />
            <input class='generic-input' type="password" name="password" placeholder="Парола" />
            <button class="generic-button" type="submit"><span>Вход</span></button>
            <button id="register-button" class="generic-button" type="button"><span>Регистрация</span></button>
       </form>

    </div>
</body>
</html> 
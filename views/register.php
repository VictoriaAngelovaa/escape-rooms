<?php
    session_start();
    // TODO: go to somewhere else if has session
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Регистрация на нов потребител</title>
        <script src="../scripts/register.js" type="module" defer></script>
        <link rel="stylesheet" href="./styles/common.css">
        <link rel="stylesheet" href="./styles/login.css">
        <link rel="stylesheet" href="./styles/register.css">
    </head>
    <body>
        <h1>ESCAPE ROOMS</h1>
        <div id="register-container">
            <form>
                <div id="error-message"></div>
                <input class='generic-input'
                       type="text"
                       placeholder="Въведете потребителско име"
                       name="username"
                       class="user-inputs"
                       />
                
                <input class='generic-input'
                       type="password"
                       placeholder="Въведете парола"
                       name="password"
                       class="user-inputs"
                       />
                
                <input class='generic-input'
                       type="password"
                       placeholder="Въведете паролата повторно"
                       name="verify-password"
                       class="user-inputs"
                       />

                <button class="generic-button" type="submit"><span>Регистрирай ме</span></button>
                <button id="already-signed-up-button" class="generic-button" type="button"><span>Вече имаш регистрация?</span></button>
           </form>
        </div>

    </body>
</html>

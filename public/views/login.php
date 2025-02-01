<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Logowanie - Manager List na Zakupy</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>

<div class="loginContainer">
    <h1>Witaj w Managerze List na Zakupy</h1>
    <div class="loginBox">
        <h2>Zaloguj się</h2>

        <form id="loginForm" action="login" method="post">
            <div class="formGroup">
                <label for="loginEmail">Adres e-mail:</label>
                <input type="email" id="loginEmail" name="email" placeholder="Podaj adres e-mail" required>
            </div>
            <div class="formGroup">
                <label for="loginPassword">Hasło:</label>
                <input type="password" id="loginPassword" name="password" placeholder="Podaj hasło" required>
            </div>
            <button type="submit">Zaloguj</button>
        </form>

        <!-- <button id="continueAsGuestBtn">Kontynuuj jako Gość</button> -->

        <p id="loginMessage" class="errorMsg">
            <?php if(isset($messages)) {
                foreach($messages as $message) {
                    echo $message;
                }
            } ?>
        </p>
    </div>
    <div class="createAccountBox">
        <p>Nie masz konta?</p>
        <a href="register" class="createAccountLink">Stwórz Konto</a>
    </div>
</div>

</body>
</html>

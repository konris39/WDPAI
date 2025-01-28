<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejestracja - Manager List na Zakupy</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>

<div class="registerPage">
    <div class="registerContainer">
        <h1>Zarejestruj się</h1>
        <div class="registerBox">
            <h2>Stwórz swoje konto</h2>

            <form id="registerForm" action="register" method="post">
                <div class="formGroup">
                    <label for="registerUsername">Nazwa użytkownika:</label>
                    <input type="text" id="registerUsername" name="username" placeholder="Podaj nazwę użytkownika" required>
                </div>
                <div class="formGroup">
                    <label for="registerEmail">Adres e-mail:</label>
                    <input type="email" id="registerEmail" name="email" placeholder="Podaj adres e-mail" required>
                </div>
                <div class="formGroup">
                    <label for="registerPassword">Hasło:</label>
                    <input type="password" id="registerPassword" name="password" placeholder="Utwórz hasło" required>
                </div>
                <div class="formGroup">
                    <label for="registerConfirmPassword">Potwierdź hasło:</label>
                    <input type="password" id="registerConfirmPassword" name="confirm_password" placeholder="Potwierdź hasło" required>
                </div>
                <button type="submit">Zarejestruj się</button>
            </form>

            <p id="registerMessage" class="errorMsg">
                <?php if(isset($messages)) {
                    foreach($messages as $message) {
                        echo $message;
                    }
                } ?>
            </p>
        </div>

        <div class="backToLoginBox">
            <p>Masz już konto?</p>
            <a href="login" class="backToLoginLink">Zaloguj się</a>
        </div>
    </div>
</div>

</body>
</html>

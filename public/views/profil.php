<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Profil Użytkownika</title>
    <link rel="stylesheet" href="public/css/style.css" />
</head>
<body>

<header>
    <h1>Twój Profil</h1>
    <nav>
        <a href="index" id="backToApp">Powrót do aplikacji</a>
        <button id="logoutBtn">Wyloguj</button>
    </nav>
</header>

<main>
    <section class="profileContainer">

        <div class="profileHeader">
            <div class="avatar">
                <img src="https://via.placeholder.com/80" alt="Avatar" />
            </div>
            <div class="userInfo">
                <h2 id="profileUsername">Użytkownik XYZ</h2>
                <p id="profileEmail">E-mail: xyz@example.com</p>
            </div>
        </div>

        <div class="profileStats">
            <h3>Twoje statystyki</h3>
            <p>Liczba sfinalizowanych list:
                <span id="totalListsCount">0</span>
            </p>
            <p>Grand Total wydanych pieniędzy:
                <span id="grandTotal">0.00 zł</span>
            </p>
        </div>

        <div class="profileActions">
            <h3>Akcje</h3>
            <button id="changePasswordBtn">Zmień hasło</button>
            <button id="clearStatsBtn">Wyczyść Statystyki</button>
        </div>

    </section>
</main>

</body>
</html>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Profil Użytkownika</title>
    <link rel="stylesheet" href="public/css/style.css" />
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 300;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border-radius: 5px;
            width: 300px;
            text-align: center;
        }
        .modal-content input {
            width: 90%;
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .modal-content button {
            margin-top: 10px;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .modal-actions {
            margin-top: 10px;
        }
    </style>
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
                <!-- Adres awatara – możesz zmienić na realny -->
                <img src="https://via.placeholder.com/80" alt="Avatar" />
            </div>
            <div class="userInfo">
                <h2 id="profileUsername"><?php echo htmlspecialchars($user->getUsername() ?? 'Użytkownik'); ?></h2>
                <p id="profileEmail">E-mail: <?php echo htmlspecialchars($user->getEmail() ?? 'email@example.com'); ?></p>
            </div>
        </div>

        <div class="profileStats">
            <!--<h3>Twoje statystyki</h3>
            <p>Liczba sfinalizowanych list:
                <span id="totalListsCount">
                    <?php echo isset($stats) ? htmlspecialchars($stats->total_finalized_lists) : '0'; ?>
                </span>
            </p> -->
            <!--<p>Grand Total wydanych pieniędzy:
                <span id="grandTotal">
                    <?php echo isset($stats) ? htmlspecialchars(number_format($stats->total_spent, 2)) : '0.00'; ?>
                 </span> zł
             </p>-->
         </div>

         <div class="profileActions">
             <h3>Akcje</h3>
             <!-- Przycisk zmiany hasła -->
            <button id="changePasswordBtn">Zmień hasło</button>
            <!-- Przycisk usunięcia konta -->
            <button id="deleteAccountBtn">Usuń Konto</button>
        </div>
    </section>
</main>

<!-- Modal dla zmiany hasła -->
<div id="changePasswordModal" class="modal">
    <div class="modal-content">
        <!-- Przycisk zamknięcia modala -->
        <span id="closeChangePassword" style="float:right; cursor:pointer;">&times;</span>
        <h3>Zmień hasło</h3>
        <!-- Formularz zmiany hasła – wysyłany metodą POST do endpointu "changePassword" -->
        <form id="changePasswordForm" action="changePassword" method="post">
            <input type="password" name="current_password" placeholder="Obecne hasło" required>
            <input type="password" name="new_password" placeholder="Nowe hasło" required>
            <input type="password" name="confirm_new_password" placeholder="Potwierdź nowe hasło" required>
            <div class="modal-actions">
                <button type="submit" style="background-color: #28a745; color: #fff;">Zmień hasło</button>
                <button type="button" id="cancelChangePassword" style="background-color: #dc3545; color: #fff;">Anuluj</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal dla usunięcia konta -->
<div id="deleteAccountModal" class="modal">
    <div class="modal-content">
        <h3>Potwierdzenie usunięcia konta</h3>
        <p>Czy na pewno chcesz usunąć swoje konto? Ta operacja jest nieodwracalna.</p>
        <!-- Formularz usunięcia konta – wysyłany metodą POST do endpointu "deleteAccount" -->
        <form id="deleteAccountForm" action="deleteAccount" method="post">
            <div class="modal-actions">
                <button type="submit" style="background-color: #dc3545; color: #fff;">Usuń Konto</button>
                <button type="button" id="cancelDeleteAccount" style="background-color: #28a745; color: #fff;">Anuluj</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Obsługa wylogowywania – przekierowanie do trasy "logout"
    document.getElementById('logoutBtn').addEventListener('click', function() {
        window.location.href = "logout";
    });

    // Obsługa otwierania modala zmiany hasła
    document.getElementById('changePasswordBtn').addEventListener('click', function() {
        document.getElementById('changePasswordModal').style.display = 'block';
    });
    document.getElementById('closeChangePassword').addEventListener('click', function() {
        document.getElementById('changePasswordModal').style.display = 'none';
    });
    document.getElementById('cancelChangePassword').addEventListener('click', function() {
        document.getElementById('changePasswordModal').style.display = 'none';
    });

    // Obsługa otwierania modala usunięcia konta
    document.getElementById('deleteAccountBtn').addEventListener('click', function() {
        document.getElementById('deleteAccountModal').style.display = 'block';
    });
    document.getElementById('cancelDeleteAccount').addEventListener('click', function() {
        document.getElementById('deleteAccountModal').style.display = 'none';
    });

    // Zamknięcie modali po kliknięciu poza obszar modal-content (opcjonalnie)
    window.onclick = function(event) {
        if (event.target == document.getElementById('changePasswordModal')) {
            document.getElementById('changePasswordModal').style.display = "none";
        }
        if (event.target == document.getElementById('deleteAccountModal')) {
            document.getElementById('deleteAccountModal').style.display = "none";
        }
    };
</script>

</body>
</html>

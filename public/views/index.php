<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Manager List na Zakupy</title>
    <link rel="stylesheet" href="public/css/style.css" />
</head>
<body>

<header>
    <h1>Manager List na Zakupy</h1>
    <nav>
        <button id="createListBtn">Stwórz Listę</button>

        <button id="toggleFavoritesBtnTop">Ulubione</button>
        <a href="profil.php" id="profileLink">Profil</a>
        <button id="logoutBtn">Wyloguj</button>
    </nav>
</header>

<main>
    <section id="pendingListsContainer">
        <h2>Twoje Listy (Oczekujące)</h2>
    </section>
</main>

<section id="finalizedListsBar">
    <h2>Sfinalizowane Listy</h2>
    <div id="finalizedListsContainer">
    </div>
</section>

<div id="favoritesPanel">
    <div class="favoritesContent">
        <button id="closeFavoritesPanelBtn" class="closeFavPanelBtn">Zamknij</button>

        <h2>Ulubione Listy</h2>
        <div id="favoritesContainer">

        </div>
    </div>
</div>

<div id="listCreationModal">
    <button class="closeModal">X</button>
    <h2>Tworzenie Nowej Listy</h2>
    <label for="listName">Nazwa Listy:</label>
    <input type="text" id="listName" placeholder="Podaj nazwę listy"/>
    <button id="confirmCreateList">Potwierdź</button>
</div>

<script src="../js/script.js"></script>

</body>
</html>
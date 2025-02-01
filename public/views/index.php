<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Manager List na Zakupy</title>
    <link rel="stylesheet" href="public/css/index.css" />
</head>
<body>
<header>
    <h1>Manager List na Zakupy</h1>
    <nav>
        <a href="createList" id="createListBtn" class="create-list-button">Stwórz Listę</a>
        <!-- <button id="toggleFavoritesBtnTop">Ulubione</button> -->
        <a href="profil" id="profileLink">Profil</a>
        <button id="logoutBtn">Wyloguj</button>
    </nav>
</header>

<main>
    <!-- Sekcja list oczekujących -->
    <div class="pending-lists-wrapper">
        <h2>Twoje Listy (Oczekujące)</h2>
        <div id="pendingListsContainer">
            <!-- Tu dynamicznie zostaną wygenerowane karty list -->
            <p>Ładowanie list...</p>
        </div>
    </div>
</main>

<!-- Pasek sfinalizowanych list (na dole strony) -->
<section id="finalizedListsBar">
    <h2>Sfinalizowane Listy</h2>
    <div id="finalizedListsContainer">
        <!-- Tu dynamicznie zostaną wygenerowane karty sfinalizowanych list -->
        <p>Ładowanie sfinalizowanych list...</p>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        function renderLists(data) {
            const pendingContainer = document.getElementById('pendingListsContainer');
            pendingContainer.innerHTML = '';
            if (data.pending.length === 0) {
                pendingContainer.innerHTML = '<p>Nie masz żadnych list zakupów w statusie "Oczekujące".</p>';
            } else {
                data.pending.forEach(list => {
                    const card = document.createElement('div');
                    card.className = 'shoppingList';
                    card.dataset.listId = list.id;
                    card.innerHTML = `
              <h3>${list.listName}</h3>
              <p class="totalCost">Łączny koszt: ${parseFloat(list.totalCost).toFixed(2)} zł</p>
              <div class="listActions">
                <button class="finalizeListBtn" data-list-id="${list.id}">Finalizuj</button>
                <button class="deleteListBtn" data-list-id="${list.id}">Usuń</button>
              </div>
              <ul class="listItems">
                ${ list.items.map(item => `
                  <li>
                    ${item.itemName} - ${item.quantity} x ${parseFloat(item.price).toFixed(2)} zł
                    <button class="deleteItemBtn" data-item-id="${item.id}">Usuń</button>
                  </li>
                `).join('') }
              </ul>
              <button type="button" class="toggleAddItemBtn">Dodaj Element</button>
              <form class="addItemForm" style="display: none;">
                <input type="hidden" name="listId" value="${list.id}">
                <input type="text" name="itemName" placeholder="Nazwa elementu" required>
                <input type="number" name="quantity" value="1" min="1" required>
                <input type="number" step="0.01" name="price" placeholder="Cena (zł)" required>
                <button type="submit">Dodaj Element</button>
              </form>
            `;
                    pendingContainer.appendChild(card);
                });
            }

            const finalizedContainer = document.getElementById('finalizedListsContainer');
            finalizedContainer.innerHTML = '';
            if (data.finalized.length === 0) {
                finalizedContainer.innerHTML = '<p>Nie masz żadnych sfinalizowanych list zakupów.</p>';
            } else {
                data.finalized.forEach(list => {
                    const card = document.createElement('div');
                    card.className = 'finalizedListSummary';
                    card.dataset.listId = list.id;
                    card.innerHTML = `
              <h4>${list.listName}</h4>
              <p>Łączny koszt: ${parseFloat(list.totalCost).toFixed(2)} zł</p>
              <button class="deleteFinalizedBtn" data-list-id="${list.id}">Usuń</button>
            `;
                    finalizedContainer.appendChild(card);
                });
            }

            document.querySelectorAll('.toggleAddItemBtn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const form = this.nextElementSibling;
                    form.style.display = (form.style.display === "flex" ? "none" : "flex");
                });
            });

            document.querySelectorAll('.addItemForm').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const dataObj = {};
                    formData.forEach((value, key) => { dataObj[key] = value; });

                    fetch('api/addItem', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(dataObj)
                    })
                        .then(response => {
                            if (!response.ok) throw new Error("Błąd sieciowy: " + response.status);
                            return response.json();
                        })
                        .then(result => {
                            console.log("Element dodany:", result);
                            fetchLists();
                        })
                        .catch(error => console.error('Błąd podczas dodawania elementu:', error));
                });
            });

            document.querySelectorAll('.finalizeListBtn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const listId = this.dataset.listId;
                    fetch('api/finalize', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ listId })
                    })
                        .then(response => response.json())
                        .then(result => {
                            console.log("Lista sfinalizowana:", result);
                            fetchLists();
                        })
                        .catch(error => console.error('Błąd finalizacji:', error));
                });
            });

            document.querySelectorAll('.deleteListBtn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const listId = this.dataset.listId;
                    fetch('api/deleteList', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ listId })
                    })
                        .then(response => response.json())
                        .then(result => {
                            console.log("Lista usunięta:", result);
                            fetchLists();
                        })
                        .catch(error => console.error('Błąd usuwania listy:', error));
                });
            });

            document.querySelectorAll('.deleteItemBtn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const itemId = this.dataset.itemId;
                    fetch('api/deleteItem', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ itemId })
                    })
                        .then(response => response.json())
                        .then(result => {
                            console.log("Element usunięty:", result);
                            fetchLists();
                        })
                        .catch(error => console.error('Błąd usuwania elementu:', error));
                });
            });

            document.querySelectorAll('.deleteFinalizedBtn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const listId = this.dataset.listId;
                    fetch('api/deleteList', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ listId })
                    })
                        .then(response => response.json())
                        .then(result => {
                            console.log("Sfinalizowana lista usunięta:", result);
                            fetchLists();
                        })
                        .catch(error => console.error('Błąd usuwania sfinalizowanej listy:', error));
                });
            });
        }

        function fetchLists() {
            fetch('api/lists')
                .then(response => {
                    if (!response.ok) throw new Error('Błąd sieciowy: ' + response.status);
                    return response.json();
                })
                .then(data => {
                    console.log("Dane z backendu:", data);
                    renderLists(data);
                })
                .catch(error => {
                    console.error('Błąd podczas pobierania list:', error);
                    document.getElementById('pendingListsContainer').innerHTML = '<p>Błąd podczas ładowania list.</p>';
                    document.getElementById('finalizedListsContainer').innerHTML = '<p>Błąd podczas ładowania list.</p>';
                });
        }

        fetchLists();
    });
    document.getElementById('logoutBtn').addEventListener('click', function() {
        window.location.href = "logout";
    });
</script>
</body>
</html>

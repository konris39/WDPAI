<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Tworzenie Nowej Listy Zakupów</title>
    <link rel="stylesheet" href="public/css/style.css" />
    <style>
        .item-group {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }
        .item-group input {
            flex: 1;
        }
        .remove-item-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 5px 10px;
            cursor: pointer;
        }
        .add-item-btn {
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 15px;
            cursor: pointer;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<header>
    <h1>Tworzenie Nowej Listy Zakupów</h1>
    <nav>
        <a href="index">Powrót do List</a>
    </nav>
</header>

<main>
    <?php if (isset($error)): ?>
        <div class="error">
            <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <form action="create" method="post">
        <label for="listName">Nazwa Listy:</label>
        <input type="text" id="listName" name="listName" required>

        <h3>Dodaj Elementy:</h3>
        <div id="itemsContainer">
            <div class="item-group">
                <input type="text" name="items[0][name]" placeholder="Nazwa elementu" required>
                <input type="number" name="items[0][quantity]" placeholder="Ilość" min="1" value="1" required>
                <input type="number" step="0.01" name="items[0][price]" placeholder="Cena (zł)" min="0" value="0.00" required>
                <button type="button" class="remove-item-btn" onclick="removeItem(this)">Usuń</button>
            </div>
        </div>
        <button type="button" class="add-item-btn" onclick="addItem()">Dodaj Kolejny Element</button>

        <button type="submit">Stwórz Listę</button>
    </form>

</main>

<script>
    let itemIndex = 1;

    function addItem() {
        const container = document.getElementById('itemsContainer');
        const itemGroup = document.createElement('div');
        itemGroup.className = 'item-group';
        itemGroup.innerHTML = `
            <input type="text" name="items[${itemIndex}][name]" placeholder="Nazwa elementu" required>
            <input type="number" name="items[${itemIndex}][quantity]" placeholder="Ilość" min="1" value="1" required>
            <input type="number" step="0.01" name="items[${itemIndex}][price]" placeholder="Cena (zł)" min="0" value="0.00" required>
            <button type="button" class="remove-item-btn" onclick="removeItem(this)">Usuń</button>
        `;
        container.appendChild(itemGroup);
        itemIndex++;
    }

    function removeItem(button) {
        const itemGroup = button.parentElement;
        itemGroup.remove();
    }
</script>

</body>
</html>

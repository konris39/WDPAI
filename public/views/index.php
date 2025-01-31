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
        <a href="createList" id="createListBtn" class="create-list-button">Stwórz Listę</a>
        <button id="toggleFavoritesBtnTop">Ulubione</button>
        <a href="profil" id="profileLink">Profil</a>
        <button id="logoutBtn">Wyloguj</button>
    </nav>
</header>

<main>
    <?php
    if (isset($error)) {
        echo '<p class="errorMsg">' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</p>';
    }
    ?>
    <section id="pendingListsContainer">
        <h2>Twoje Listy (Oczekujące)</h2>
        <?php if (!empty($listsWithItems)): ?>
            <?php
            $hasPending = false;
            foreach($listsWithItems as $entry):
                $list = $entry['list'];
                $items = $entry['items'];
                if($list->getStatus() === 'pending'):
                    $hasPending = true;
                    ?>
                    <div class="shoppingList">
                        <h3><?= htmlspecialchars($list->getListName()) ?></h3>
                        <p>Łączny koszt: <?= number_format($list->getTotalCost(), 2) ?> zł</p>

                        <!-- Formularz do finalizacji listy -->
                        <form action="finalize" method="post" style="display:inline;">
                            <input type="hidden" name="listId" value="<?= $list->getId() ?>">
                            <button type="submit" class="finalizeListBtn">Finalizuj</button>
                        </form>

                        <!-- Formularz do usuwania listy -->
                        <form action="delete" method="post" style="display:inline;">
                            <input type="hidden" name="listId" value="<?= $list->getId() ?>">
                            <button type="submit" class="deleteListBtn">Usuń</button>
                        </form>

                        <!-- Formularz do dodawania elementu -->
                        <form action="addItem" method="post" class="addItemForm">
                            <input type="hidden" name="listId" value="<?= $list->getId() ?>">
                            <input type="text" name="itemName" placeholder="Nazwa elementu" required>
                            <input type="number" name="quantity" value="1" min="1" required>
                            <input type="number" step="0.01" name="price" placeholder="Cena (zł)" required>
                            <button type="submit" class="addItemBtn">Dodaj Element</button>
                        </form>

                        <!-- Wyświetlanie elementów listy -->
                        <?php if (!empty($items)): ?>
                            <ul>
                                <?php foreach($items as $item): ?>
                                    <li>
                                        <?= htmlspecialchars($item->getItemName()) ?> - <?= $item->getQuantity() ?> x <?= number_format($item->getPrice(), 2) ?> zł
                                        <!-- Formularz do usuwania elementu -->
                                        <form action="deleteItem" method="post" style="display:inline;">
                                            <input type="hidden" name="itemId" value="<?= $item->getId() ?>">
                                            <button type="submit" class="deleteItemBtn">Usuń</button>
                                        </form>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>Brak elementów w tej liście.</p>
                        <?php endif; ?>
                    </div>
                <?php
                endif;
            endforeach;

            if (!$hasPending):
                ?>
                <p>Nie masz żadnych list zakupów w statusie "Oczekujące".</p>
            <?php endif; ?>
        <?php else: ?>
            <p>Nie masz żadnych list zakupów.</p>
        <?php endif; ?>
    </section>
</main>

<section id="finalizedListsBar">
    <h2>Sfinalizowane Listy</h2>
    <div id="finalizedListsContainer">
        <?php if (!empty($listsWithItems)): ?>
            <?php
            $hasFinalized = false;
            foreach($listsWithItems as $entry):
                $list = $entry['list'];
                if ($list->getStatus() === 'finalized'):
                    $hasFinalized = true;
                    ?>
                    <div class="finalizedListSummary">
                        <h4><?= htmlspecialchars($list->getListName()) ?></h4>
                        <p>Łączny koszt: <?= number_format($list->getTotalCost(), 2) ?> zł</p>
                        <form action="delete" method="post">
                            <input type="hidden" name="listId" value="<?= $list->getId() ?>">
                            <button type="submit" class="deleteFinalizedBtn">Usuń</button>
                        </form>
                    </div>
                <?php
                endif;
            endforeach;

            if (!$hasFinalized):
                ?>
                <p>Nie masz żadnych sfinalizowanych list zakupów.</p>
            <?php endif; ?>
        <?php else: ?>
            <p>Nie masz żadnych sfinalizowanych list zakupów.</p>
        <?php endif; ?>
    </div>
</section>

<!-- Pozostały kod widoku -->

</body>
</html>

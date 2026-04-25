<?php
include('config.php');
session_start();
require_once("functions.php");

global $yhendus;
global $kasutajanimi;

/* Проверка доступа: только worker и admin */
if (!isset($_SESSION['role']) || $_SESSION['role'] < 1) {
    header("Location: ?leht=login.php");
    exit();
}

$categories = kysiJoogiKategooriad();

/* Значения по умолчанию */
$sorttulp = "Nimetus";
$otsisona = "";
$category = "";

/* Сортировка */
if (isset($_GET["sort"])) {
    $sorttulp = $_REQUEST["sort"];
}

/* Поиск */
if (isset($_GET["otsisona"])) {
    $otsisona = $_REQUEST["otsisona"];
}

/* Фильтр категории */
if (isset($_GET["category"])) {
    $category = $_REQUEST["category"];
}

/* Добавление новой записи — только admin */
if (isset($_POST["lisa"]) && isAdmin()) {
    $Name = htmlspecialchars(trim($_POST["Name"]));
    $Description = htmlspecialchars(trim($_POST["Description"] ?? ""));
    $Price = htmlspecialchars(trim($_POST["Price"]));
    $Category = htmlspecialchars(trim($_POST["Category"]));
    $IsAvailable = isset($_POST["IsAvailable"]) ? (int)$_POST["IsAvailable"] : 1;

    if ($Name !== "") {
        lisaJook($Name, $Description, $Price, $Category, $IsAvailable);
    }

    header("Location: index.php?leht=adminJoogid.php");
    exit();
}

$jookideNimekiri = kysiJoogid($sorttulp, $otsisona, $category);

/* Изменение */
if (isset($_POST["muutmine"]) && isWorker()) {
    $muudetudid = (int)$_POST["muudetudid"];
    $IsAvailable = (int)$_POST["IsAvailable"];

    if ($muudetudid > 0) {

        if (isAdmin()) {
            $Name = htmlspecialchars(trim($_POST["Name"] ?? ""));
            $Description = htmlspecialchars(trim($_POST["Description"] ?? ""));
            $Price = htmlspecialchars(trim($_POST["Price"] ?? 0));
            $Category = htmlspecialchars(trim($_POST["Category"] ?? ""));

            if ($Name !== "" && $Category !== "") {
                muudaJook($muudetudid, $Name, $Description, $Price, $Category, $IsAvailable);
            }
        }
        elseif ($_SESSION['role'] == 1) {
            muudaJoogiStaatus($muudetudid, $IsAvailable);
        }
    }

    header("Location: index.php?leht=adminJoogid.php&sort=" . urlencode($sorttulp) . "&otsisona=" . urlencode($otsisona) . "&category=" . urlencode($category));
    exit();
}

/* Удаление — только admin */
if (isset($_GET["kustutaid"]) && isAdmin()) {
    kustutaJook((int)$_GET["kustutaid"]);
    header("Location: index.php?leht=adminJoogid.php");
    exit();
}
?>

<section class="adminPiibudPage">
    <div class="adminLayout">

        <aside class="glass adminSidebar">
            <?php if (isAdmin()): ?>
                <nav class="adminNav">
                    <a href="index.php?leht=adminPiibud.php" class="adminNavLink">Piibud</a>
                    <a href="index.php?leht=adminJoogid.php" class="adminNavLink active">Joogid</a>
                    <a href="index.php?leht=adminSnakid.php" class="adminNavLink">Toidud</a>
                    <a href="index.php?leht=adminBroneeringud.php" class="adminNavLink">Broneeringud</a>
                    <a href="index.php?leht=adminUsers.php" class="adminNavLink">Kasutajad</a>
                    <a href="index.php?leht=adminGraafik.php" class="adminNavLink">Graafik</a>
                </nav>
            <?php else: ?>
                <nav class="adminNav">
                    <a href="index.php?leht=adminPiibud.php" class="adminNavLink">Piibud</a>
                    <a href="index.php?leht=adminJoogid.php" class="adminNavLink active">Joogid</a>
                    <a href="index.php?leht=adminSnakid.php" class="adminNavLink">Toidud</a>
                    <a href="index.php?leht=adminBroneeringud.php" class="adminNavLink">Broneeringud</a>
                    <a href="index.php?leht=adminGraafik.php" class="adminNavLink">Graafik</a>
                </nav>
            <?php endif; ?>
        </aside>

        <div class="adminContent">
            <div class="menuuHeader">
                <h1 class="menuuTitle">JOOGID</h1>
                <div class="menuuTitleLine"></div>
            </div>

            <div class="dashboardInfo">
                <p class="dashboardDiscription">
                    <?php if (isAdmin()): ?>
                        Halda jookide nimekirja, hindu ja staatust.
                    <?php else: ?>
                        Muuda jookide staatust.
                    <?php endif; ?>
                </p>
            </div>

            <div class="adminPanelCard">

                <form method="get" action="index.php" class="adminTtoolbar">
                    <input type="hidden" name="leht" value="adminJoogid.php">
                    <input type="hidden" name="sort" value="<?= htmlspecialchars($sorttulp) ?>">

                    <div class="adminOtsingBox">
                        <input
                            type="text"
                            name="otsisona"
                            class="adminPiibuOtsing"
                            placeholder="Otsi jook..."
                            value="<?= htmlspecialchars($otsisona) ?>"
                        >
                    </div>

                    <?php if (isAdmin()): ?>
                        <button type="button" id="toggleFormBtn" class="btn">+ Lisa uus jook</button>
                    <?php endif; ?>
                </form>

                <?php if (isAdmin()): ?>
                    <div id="addForm" class="glass lisaInfoVorm formCard hidden">
                        <h2 class="formTitle dashboardItemTitle">Uue joogi lisamine</h2>

                        <form method="post" action="index.php?leht=adminJoogid.php">
                            <div class="formGrid">

                                <div class="formGroup">
                                    <input
                                            type="text"
                                            name="Name"
                                            placeholder="Joogi nimetus"
                                            required
                                    >
                                </div>

                                <div class="formGroup">
                                    <input
                                            type="text"
                                            name="Description"
                                            placeholder="Kirjeldus"
                                    >
                                </div>

                                <div class="formGroup">
                                    <input
                                            type="number"
                                            step="0.01"
                                            min="0"
                                            name="Price"
                                            placeholder="Hind"
                                            required
                                    >
                                </div>

                                <div class="formGroup">
                                    <div class="customSelectWrapper">
                                        <select name="Category" class="customSelectNative" required>
                                            <option value="">Vali kategooria</option>
                                            <?php foreach ($categories as $cat): ?>
                                                <option value="<?= htmlspecialchars($cat['Category']) ?>">
                                                    <?= htmlspecialchars($cat['Category']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>

                                        <div class="customSelectTrigger">
                                            <span class="customSelectText">Vali kategooria</span>
                                            <span class="customSelectArrow">▾</span>
                                        </div>

                                        <div class="customSelectDropdown">
                                            <?php foreach ($categories as $cat): ?>
                                                <div
                                                        class="customSelectOption"
                                                        data-value="<?= htmlspecialchars($cat['Category']) ?>"
                                                >
                                                    <?= htmlspecialchars($cat['Category']) ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="formGroup">
                                    <div class="customSelectWrapper">
                                        <select name="IsAvailable" class="customSelectNative">
                                            <option value="1">Aktiivne</option>
                                            <option value="0">Peidetud</option>
                                        </select>

                                        <div class="customSelectTrigger">
                                            <span class="customSelectText">Aktiivne</span>
                                            <span class="customSelectArrow">▾</span>
                                        </div>

                                        <div class="customSelectDropdown">
                                            <div class="customSelectOption selected" data-value="1">Aktiivne</div>
                                            <div class="customSelectOption" data-value="0">Peidetud</div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="formActions">
                                <button type="submit" name="lisa" class="btn">Salvesta</button>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>

                <div class="adminTabeliAlus">
                    <table class="adminTabel">
                        <thead>
                        <tr>
                            <td>
                                <a href="?leht=adminJoogid.php&sort=id&otsisona=<?= urlencode($otsisona) ?>&category=<?= urlencode($category) ?>">
                                    ID
                                </a>
                            </td>
                            <td>
                                <a href="?leht=adminJoogid.php&sort=nimetus&otsisona=<?= urlencode($otsisona) ?>&category=<?= urlencode($category) ?>">
                                    Nimetus
                                </a>
                            </td>
                            <td>
                                <a href="?leht=adminJoogid.php&sort=kategooria&otsisona=<?= urlencode($otsisona) ?>&category=<?= urlencode($category) ?>">
                                    Kategooria
                                </a>
                            </td>
                            <td>Kirjeldus</td>
                            <td>
                                <a href="?leht=adminJoogid.php&sort=hind&otsisona=<?= urlencode($otsisona) ?>&category=<?= urlencode($category) ?>">
                                    Hind
                                </a>
                            </td>
                            <td>
                                <a href="?leht=adminJoogid.php&sort=staatus&otsisona=<?= urlencode($otsisona) ?>&category=<?= urlencode($category) ?>">
                                    Staatus
                                </a>
                            </td>
                            <td>Tegevused</td>
                        </tr>
                        </thead>

                        <tbody id="piibudTableBody">
                        <?php if (!empty($jookideNimekiri)): ?>
                            <?php foreach ($jookideNimekiri as $jook): ?>

                                <?php if (isset($_GET["muutmisid"]) && intval($_GET["muutmisid"]) === intval($jook->Id) && isWorker()): ?>
                                    <tr class="editRow">
                                        <td colspan="7">
                                            <form method="post"
                                                  action="index.php?leht=adminJoogid.php&sort=<?= urlencode($sorttulp) ?>&otsisona=<?= urlencode($otsisona) ?>&category=<?= urlencode($category) ?>"
                                                  class="adminEditForm">

                                                <input type="hidden" name="muudetudid" value="<?= htmlspecialchars($jook->Id) ?>">

                                                <div class="formGrid">

                                                    <?php if (isAdmin()): ?>

                                                        <div class="formGroup">
                                                            <input
                                                                    type="text"
                                                                    name="Name"
                                                                    value="<?= htmlspecialchars($jook->Name) ?>"
                                                                    placeholder="Joogi nimetus"
                                                                    required
                                                            >
                                                        </div>

                                                        <div class="formGroup">
                                                            <input
                                                                    type="text"
                                                                    name="Description"
                                                                    value="<?= htmlspecialchars($jook->Description ?? '') ?>"
                                                                    placeholder="Kirjeldus"
                                                            >
                                                        </div>

                                                        <div class="formGroup">
                                                            <input
                                                                    type="number"
                                                                    step="0.01"
                                                                    min="0"
                                                                    name="Price"
                                                                    value="<?= htmlspecialchars($jook->Price) ?>"
                                                                    placeholder="Hind"
                                                                    required
                                                            >
                                                        </div>
                                                        <div class="formGroup">
                                                            <div class="customSelectWrapper">
                                                                <select name="Category" class="customSelectNative" required>
                                                                    <option value="">Vali kategooria</option>
                                                                    <?php foreach ($categories as $cat): ?>
                                                                        <option
                                                                                value="<?= htmlspecialchars($cat['Category']) ?>"
                                                                                <?= ($jook->Category === $cat['Category']) ? 'selected' : '' ?>
                                                                        >
                                                                            <?= htmlspecialchars($cat['Category']) ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>

                                                                <div class="customSelectTrigger">
                                                                    <span class="customSelectText"><?= htmlspecialchars($jook->Category) ?></span>
                                                                    <span class="customSelectArrow">▾</span>
                                                                </div>

                                                                <div class="customSelectDropdown">
                                                                    <?php foreach ($categories as $cat): ?>
                                                                        <div
                                                                                class="customSelectOption <?= ($jook->Category === $cat['Category']) ? 'selected' : '' ?>"
                                                                                data-value="<?= htmlspecialchars($cat['Category']) ?>"
                                                                        >
                                                                            <?= htmlspecialchars($cat['Category']) ?>
                                                                        </div>
                                                                    <?php endforeach; ?>
                                                                </div>
                                                            </div>
                                                        </div>


                                                    <?php else: ?>

                                                        <div class="formGroup">
                                                            <input
                                                                type="text"
                                                                value="<?= htmlspecialchars($jook->Name) ?>"
                                                                disabled
                                                            >
                                                        </div>

                                                        <div class="formGroup">
                                                            <input
                                                                type="text"
                                                                value="<?= htmlspecialchars($jook->Category) ?>"
                                                                disabled
                                                            >
                                                        </div>

                                                        <div class="formGroup">
                                                            <input
                                                                type="text"
                                                                value="<?= htmlspecialchars($jook->Description ?? '-') ?>"
                                                                disabled
                                                            >
                                                        </div>

                                                    <?php endif; ?>

                                                    <div class="formGroup">
                                                        <div class="customSelectWrapper">
                                                            <select name="IsAvailable" class="customSelectNative">
                                                                <option value="1" <?= ((int)$jook->IsAvailable === 1) ? 'selected' : '' ?>>Aktiivne</option>
                                                                <option value="0" <?= ((int)$jook->IsAvailable === 0) ? 'selected' : '' ?>>Peidetud</option>
                                                            </select>

                                                            <div class="customSelectTrigger">
                                                                <span class="customSelectText">
                                                                    <?= ((int)$jook->IsAvailable === 1) ? 'Aktiivne' : 'Peidetud' ?>
                                                                </span>
                                                                <span class="customSelectArrow">▾</span>
                                                            </div>

                                                            <div class="customSelectDropdown">
                                                                <div class="customSelectOption <?= ((int)$jook->IsAvailable === 1) ? 'selected' : '' ?>" data-value="1">Aktiivne</div>
                                                                <div class="customSelectOption <?= ((int)$jook->IsAvailable === 0) ? 'selected' : '' ?>" data-value="0">Peidetud</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="formActions">
                                                    <button type="submit" name="muutmine" class="btn">Muuda</button>
                                                    <a href="index.php?leht=adminJoogid.php&sort=<?= urlencode($sorttulp) ?>&otsisona=<?= urlencode($otsisona) ?>&category=<?= urlencode($category) ?>" class="btn katkestaBtn">
                                                        Katkesta
                                                    </a>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <tr>
                                        <td><?= htmlspecialchars($jook->Id) ?></td>
                                        <td><?= htmlspecialchars($jook->Name) ?></td>
                                        <td><?= htmlspecialchars($jook->Category) ?></td>
                                        <td><?= htmlspecialchars($jook->Description ?? '-') ?></td>
                                        <td><?= htmlspecialchars($jook->Price) ?> €</td>
                                        <td>
                                            <?php if ((int)$jook->IsAvailable === 1): ?>
                                                <span class="statusBadge activeStatus">
                                                    <span class="statusDot"></span>
                                                    Aktiivne
                                                </span>
                                            <?php else: ?>
                                                <span class="statusBadge hiddenStatus">
                                                    <span class="statusDot"></span>
                                                    Peidetud
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="index.php?leht=adminJoogid.php&muutmisid=<?= urlencode($jook->Id) ?>&sort=<?= urlencode($sorttulp) ?>&otsisona=<?= urlencode($otsisona) ?>&category=<?= urlencode($category) ?>" class="tableActionBtn muudaBtn">
                                                Muuda
                                            </a>

                                            <?php if (isAdmin()): ?>
                                                <a href="index.php?leht=adminJoogid.php&kustutaid=<?= urlencode($jook->Id) ?>"
                                                   onclick="return confirm('Kas kustutada see jook?')" class="tableActionBtn kustutaBtn">
                                                    Kustuta
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>

                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8">Tulemusi ei leitud.</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</section>
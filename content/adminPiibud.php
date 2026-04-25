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

/* Получаем список брендов / категорий */
$categories = [];
$result = $yhendus->query("SELECT Id, Name FROM HookahBrands ORDER BY Name");

while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

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
    $flavorName = htmlspecialchars(trim($_POST["flavorName"]));
    $hookahBrandId = (int)$_POST["hookahBrandId"];
    $description = htmlspecialchars(trim($_POST["description"] ?? ""));
    $isAvailable = isset($_POST["isAvailable"]) ? (int)$_POST["isAvailable"] : 1;

    if ($flavorName !== "" && $hookahBrandId > 0) {
        lisaPiip($flavorName, $hookahBrandId, $description, $isAvailable);
    }

    header("Location: index.php?leht=adminPiibud.php");
    exit();
}

/* Получаем список брендов */
$brands = kysiHookahBrands();

/* Получаем список вкусов */
$piibudeNimekiri = kysiPiibud($sorttulp, $otsisona, $category);

/* Изменение */
if (isset($_POST["muutmine"]) && isWorker()) {
    $muudetudid = (int)$_POST["muudetudid"];
    $isAvailable = (int)$_POST["isAvailable"];

    if ($muudetudid > 0) {

        /* Admin может менять всё */
        if (isAdmin()) {
            $flavorName = htmlspecialchars(trim($_POST["flavorName"] ?? ""));
            $hookahBrandId = (int)($_POST["hookahBrandId"] ?? 0);
            $description = htmlspecialchars(trim($_POST["description"] ?? ""));

            if ($flavorName !== "" && $hookahBrandId > 0) {
                muudaPiip($muudetudid, $flavorName, $hookahBrandId, $description, $isAvailable);
            }
        }

        /* Worker может менять только статус */
        elseif ($_SESSION['role'] == 1) {
            muudaPiibuStaatus($muudetudid, $isAvailable);
        }
    }

    header("Location: index.php?leht=adminPiibud.php&sort=" . urlencode($sorttulp) . "&otsisona=" . urlencode($otsisona) . "&category=" . urlencode($category));
    exit();
}

/* Удаление — только admin */
if (isset($_GET["kustutaid"]) && isAdmin()) {
    kustutaPiip((int)$_GET["kustutaid"]);
    header("Location: index.php?leht=adminPiibud.php");
    exit();
}
?>

<section class="adminPiibudPage">
    <div class="adminLayout">

        <aside class="glass adminSidebar">
            <?php if (isAdmin()): ?>
                <nav class="adminNav">
                    <a href="index.php?leht=adminPiibud.php" class="adminNavLink active">Piibud</a>
                    <a href="index.php?leht=adminJoogid.php" class="adminNavLink">Joogid</a>
                    <a href="index.php?leht=adminSnakid.php" class="adminNavLink">Toidud</a>
                    <a href="index.php?leht=adminBroneeringud.php" class="adminNavLink">Broneeringud</a>
                    <a href="index.php?leht=adminUsers.php" class="adminNavLink">Kasutajad</a>
                    <a href="index.php?leht=adminGraafik.php" class="adminNavLink">Graafik</a>
                </nav>
            <?php else: ?>
                <nav class="adminNav">
                    <a href="index.php?leht=adminPiibud.php" class="adminNavLink active">Piibud</a>
                    <a href="index.php?leht=adminJoogid.php" class="adminNavLink">Joogid</a>
                    <a href="index.php?leht=adminSnakid.php" class="adminNavLink">Toidud</a>
                    <a href="index.php?leht=adminBroneeringud.php" class="adminNavLink">Broneeringud</a>
                    <a href="index.php?leht=adminGraafik.php" class="adminNavLink">Graafik</a>
                </nav>
            <?php endif; ?>

            <div class="adminSidebarBottom">
                <a href="?leht=logout.php" class="adminLogout">Logout</a>
            </div>
        </aside>

        <div class="adminContent">
            <div class="menuuHeader">
                <h1 class="menuuTitle">PIIBUD</h1>
                <div class="menuuTitleLine"></div>
            </div>

            <div class="dashboardInfo">
                <p class="dashboardDiscription">
                    <?php if (isAdmin()): ?>
                        Halda vesipiipude nimekirja, hindu ja maitseid.
                    <?php else: ?>
                        Muuda vesipiipude staatust.
                    <?php endif; ?>
                </p>
            </div>

            <div class="adminPanelCard">

                <form method="get" action="index.php" class="adminTtoolbar">
                    <input type="hidden" name="leht" value="adminPiibud.php">
                    <input type="hidden" name="sort" value="<?= htmlspecialchars($sorttulp) ?>">

                    <div class="adminOtsingBox">
                        <input
                                type="text"
                                name="otsisona"
                                class="adminPiibuOtsing"
                                placeholder="Otsi piipu..."
                                value="<?= htmlspecialchars($otsisona) ?>"
                        >
                    </div>

                    <?php if (isAdmin()): ?>
                        <button type="button" id="toggleFormBtn" class="btn">+ Lisa uus piip</button>
                    <?php endif; ?>
                </form>

                <?php if (isAdmin()): ?>
                    <div id="addForm" class="glass lisaInfoVorm formCard hidden">
                        <h2 class="formTitle dashboardItemTitle">Uue maitse lisamine</h2>

                        <form method="post" action="index.php?leht=adminPiibud.php">
                            <div class="formGrid">

                                <div class="formGroup">
                                    <input
                                            type="text"
                                            name="flavorName"
                                            placeholder="Maitse nimetus"
                                            required
                                    >
                                </div>

                                <div class="formGroup">
                                    <div class="customSelectWrapper">
                                        <select name="hookahBrandId" class="customSelectNative" required>
                                            <option value="">Vali kategooria</option>
                                            <?php foreach ($categories as $cat): ?>
                                                <option value="<?= htmlspecialchars($cat['Id']) ?>">
                                                    <?= htmlspecialchars($cat['Name']) ?>
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
                                                        data-value="<?= htmlspecialchars($cat['Id']) ?>"
                                                >
                                                    <?= htmlspecialchars($cat['Name']) ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="formGroup">
                                    <input
                                            type="text"
                                            name="description"
                                            placeholder="Kirjeldus"
                                    >
                                </div>

                                <div class="formGroup">
                                    <div class="customSelectWrapper">
                                        <select name="isAvailable" class="customSelectNative">
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
                                <a href="?leht=adminPiibud.php&sort=id&otsisona=<?= urlencode($otsisona) ?>&category=<?= urlencode($category) ?>">
                                    ID
                                </a>
                            </td>
                            <td>
                                <a href="?leht=adminPiibud.php&sort=nimetus&otsisona=<?= urlencode($otsisona) ?>&category=<?= urlencode($category) ?>">
                                    Nimetus
                                </a>
                            </td>
                            <td>
                                <a href="?leht=adminPiibud.php&sort=kategooria&otsisona=<?= urlencode($otsisona) ?>&category=<?= urlencode($category) ?>">
                                    Kategooria
                                </a>
                            </td>
                            <td>Kirjeldus</td>
                            <td>
                                <a href="?leht=adminPiibud.php&sort=taishind&otsisona=<?= urlencode($otsisona) ?>&category=<?= urlencode($category) ?>">
                                    Täishind
                                </a>
                            </td>
                            <td>
                                <a href="?leht=adminPiibud.php&sort=kliendihind&otsisona=<?= urlencode($otsisona) ?>&category=<?= urlencode($category) ?>">
                                    Kliendihind
                                </a>
                            </td>
                            <td>
                                <a href="?leht=adminPiibud.php&sort=staatus&otsisona=<?= urlencode($otsisona) ?>&category=<?= urlencode($category) ?>">
                                    Staatus
                                </a>
                            </td>
                            <td>Tegevused</td>
                        </tr>
                        </thead>

                        <tbody id="piibudTableBody">
                        <?php if (!empty($piibudeNimekiri)): ?>
                            <?php foreach ($piibudeNimekiri as $piip): ?>

                                <?php if (isset($_GET["muutmisid"]) && intval($_GET["muutmisid"]) === intval($piip->Id) && isWorker()): ?>
                                    <tr class="editRow">
                                        <td colspan="8">
                                            <form method="post"
                                                  action="index.php?leht=adminPiibud.php&sort=<?= urlencode($sorttulp) ?>&otsisona=<?= urlencode($otsisona) ?>&category=<?= urlencode($category) ?>"
                                                  class="adminEditForm">

                                                <input type="hidden" name="muudetudid" value="<?= htmlspecialchars($piip->Id) ?>">

                                                <div class="formGrid">

                                                    <?php if (isAdmin()): ?>

                                                        <div class="formGroup">
                                                            <input
                                                                    type="text"
                                                                    name="flavorName"
                                                                    value="<?= htmlspecialchars($piip->FlavorName) ?>"
                                                                    placeholder="Maitse nimetus"
                                                                    required
                                                            >
                                                        </div>
                                                        <div class="formGroup">
                                                            <input
                                                                    type="text"
                                                                    name="description"
                                                                    value="<?= htmlspecialchars($piip->Description ?? '') ?>"
                                                                    placeholder="Kirjeldus"
                                                            >
                                                        </div>

                                                        <div class="formGroup">
                                                            <div class="customSelectWrapper">
                                                                <select name="hookahBrandId" class="customSelectNative" required>
                                                                    <option value="">Vali kategooria</option>
                                                                    <?php foreach ($categories as $cat): ?>
                                                                        <option
                                                                                value="<?= htmlspecialchars($cat['Id']) ?>"
                                                                                <?= ((int)$piip->HookahBrandId === (int)$cat['Id']) ? 'selected' : '' ?>
                                                                        >
                                                                            <?= htmlspecialchars($cat['Name']) ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>

                                                                <div class="customSelectTrigger">
                                                                    <span class="customSelectText">
                                                                        <?php
                                                                        $selectedCategoryName = "Vali kategooria";
                                                                        foreach ($categories as $cat) {
                                                                            if ((int)$piip->HookahBrandId === (int)$cat['Id']) {
                                                                                $selectedCategoryName = $cat['Name'];
                                                                                break;
                                                                            }
                                                                        }
                                                                        echo htmlspecialchars($selectedCategoryName);
                                                                        ?>
                                                                    </span>
                                                                    <span class="customSelectArrow">▾</span>
                                                                </div>

                                                                <div class="customSelectDropdown">
                                                                    <?php foreach ($categories as $cat): ?>
                                                                        <div
                                                                                class="customSelectOption <?= ((int)$piip->HookahBrandId === (int)$cat['Id']) ? 'selected' : '' ?>"
                                                                                data-value="<?= htmlspecialchars($cat['Id']) ?>"
                                                                        >
                                                                            <?= htmlspecialchars($cat['Name']) ?>
                                                                        </div>
                                                                    <?php endforeach; ?>
                                                                </div>
                                                            </div>
                                                        </div>



                                                    <?php else: ?>

                                                        <div class="formGroup">
                                                            <input
                                                                    type="text"
                                                                    value="<?= htmlspecialchars($piip->FlavorName) ?>"
                                                                    disabled
                                                            >
                                                        </div>

                                                        <div class="formGroup">
                                                            <input
                                                                    type="text"
                                                                    value="<?= htmlspecialchars($piip->Name) ?>"
                                                                    disabled
                                                            >
                                                        </div>

                                                        <div class="formGroup">
                                                            <input
                                                                    type="text"
                                                                    value="<?= htmlspecialchars($piip->Description ?? '-') ?>"
                                                                    disabled
                                                            >
                                                        </div>

                                                    <?php endif; ?>

                                                    <div class="formGroup">
                                                        <div class="customSelectWrapper">
                                                            <select name="isAvailable" class="customSelectNative">
                                                                <option value="1" <?= ((int)$piip->IsAvailable === 1) ? 'selected' : '' ?>>Aktiivne</option>
                                                                <option value="0" <?= ((int)$piip->IsAvailable === 0) ? 'selected' : '' ?>>Peidetud</option>
                                                            </select>

                                                            <div class="customSelectTrigger">
                                                                <span class="customSelectText">
                                                                    <?= ((int)$piip->IsAvailable === 1) ? 'Aktiivne' : 'Peidetud' ?>
                                                                </span>
                                                                <span class="customSelectArrow">▾</span>
                                                            </div>

                                                            <div class="customSelectDropdown">
                                                                <div class="customSelectOption <?= ((int)$piip->IsAvailable === 1) ? 'selected' : '' ?>" data-value="1">Aktiivne</div>
                                                                <div class="customSelectOption <?= ((int)$piip->IsAvailable === 0) ? 'selected' : '' ?>" data-value="0">Peidetud</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="formActions">
                                                    <button type="submit" name="muutmine" class="btn">Muuda</button>
                                                    <a href="index.php?leht=adminPiibud.php&sort=<?= urlencode($sorttulp) ?>&otsisona=<?= urlencode($otsisona) ?>&category=<?= urlencode($category) ?>" class="btn katkestaBtn">
                                                        Katkesta
                                                    </a>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <tr>
                                        <td><?= htmlspecialchars($piip->Id) ?></td>
                                        <td><?= htmlspecialchars($piip->FlavorName) ?></td>
                                        <td><?= htmlspecialchars($piip->Name) ?></td>
                                        <td><?= htmlspecialchars($piip->Description ?? '-') ?></td>
                                        <td><?= htmlspecialchars($piip->RegularPrice) ?> €</td>
                                        <td><?= htmlspecialchars($piip->ClientPrice) ?> €</td>
                                        <td>
                                            <?php if ((int)$piip->IsAvailable === 1): ?>
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
                                            <a href="index.php?leht=adminPiibud.php&muutmisid=<?= urlencode($piip->Id) ?>&sort=<?= urlencode($sorttulp) ?>&otsisona=<?= urlencode($otsisona) ?>&category=<?= urlencode($category) ?>" class="tableActionBtn muudaBtn">
                                                Muuda
                                            </a>

                                            <?php if (isAdmin()): ?>
                                                <a href="index.php?leht=adminPiibud.php&kustutaid=<?= urlencode($piip->Id) ?>"
                                                    onclick="return confirm('Kas kustutada see maitse?')" class="tableActionBtn kustutaBtn">
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
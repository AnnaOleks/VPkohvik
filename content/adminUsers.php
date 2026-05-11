<?php
include('config.php');
session_start();
require_once("functions.php");

global $yhendus;
global $kasutajanimi;

/* Проверка доступа: только worker и admin */
if (!isAdmin()) {
    header("Location: ?leht=login.php");
    exit();
}

/* Значения по умолчанию */
$sorttulp = "Kasutajanimi";
$otsisona = "";

/* Сортировка */
if (isset($_GET["sort"])) {
    $sorttulp = $_REQUEST["sort"];
}

/* Поиск */
if (isset($_GET["otsisona"])) {
    $otsisona = $_REQUEST["otsisona"];
}

/* Добавление новой записи — только admin */
if (isset($_POST["lisa"]) && isAdmin()) {
    $UserName = htmlspecialchars(trim($_POST["kasutajanimi"] ?? ""));
    $Email = htmlspecialchars(trim($_POST["epost"] ?? ""));
    $Password = trim($_POST["password"] ?? "");
    $Role = (int)($_POST["role"] ?? 0);

    if ($UserName !== "" && $Password !== "") {
        lisaUser($UserName, $Email, $Password, $Role);
    }

    header("Location: index.php?leht=adminUsers.php&sort=" . urlencode($sorttulp) . "&otsisona=" . urlencode($otsisona));
    exit();
}

$usersNimekiri = kysiUser($sorttulp, $otsisona);

/* Изменение */
if (isset($_POST["muutmine"]) && isAdmin()) {
    $muudetudid = (int)$_POST["muudetudid"];

    if ($muudetudid > 0) {

        $UserName = htmlspecialchars(trim($_POST["kasutajanimi"] ?? ""));
        $Email = htmlspecialchars(trim($_POST["epost"] ?? ""));
        $Role = (int)($_POST["role"] ?? 0);

        if ($UserName !== "") {
            muudaUser($muudetudid, $UserName, $Email, $Role);
        }
    }

    header("Location: index.php?leht=adminUsers.php&sort=" . urlencode($sorttulp) . "&otsisona=" . urlencode($otsisona) );
    exit();
}

/* Удаление — только admin */
if (isset($_GET["kustutaid"]) && isAdmin()) {
    kustutaUser((int)$_GET["kustutaid"]);
    header("Location: index.php?leht=adminUsers.php");
    exit();
}
?>

<section class="adminPiibudPage">
    <div class="adminLayout">

        <aside class="glass adminSidebar">
            <?php if (isAdmin()): ?>
                <nav class="adminNav">
                    <a href="index.php?leht=adminPiibud.php" class="adminNavLink">Piibud</a>
                    <a href="index.php?leht=adminJoogid.php" class="adminNavLink">Joogid</a>
                    <a href="index.php?leht=adminSnakid.php" class="adminNavLink">Toidud</a>
                    <a href="index.php?leht=adminBroneeringud.php" class="adminNavLink">Broneeringud</a>
                    <a href="index.php?leht=adminUsers.php" class="adminNavLink active">Kasutajad</a>
                    <a href="index.php?leht=adminGraafik.php" class="adminNavLink">Graafik</a>
                </nav>
            <?php else: ?>
                <nav class="adminNav">
                    <a href="index.php?leht=adminPiibud.php" class="adminNavLink">Piibud</a>
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
                <h1 class="menuuTitle">KASUTAJAD</h1>
                <div class="menuuTitleLine"></div>
            </div>

            <div class="dashboardInfo">
                <p class="dashboardDiscription">
                    Halda kasutajate nimekirja.
                </p>
            </div>

            <div class="adminPanelCard">

                <form method="get" action="index.php" class="adminTtoolbar">
                    <input type="hidden" name="leht" value="adminUsers.php">
                    <input type="hidden" name="sort" value="<?= htmlspecialchars($sorttulp) ?>">

                    <div class="adminOtsingBox">
                        <input
                            type="text"
                            name="otsisona"
                            class="adminPiibuOtsing"
                            placeholder="Otsi kasutaja..."
                            value="<?= htmlspecialchars($otsisona) ?>"
                        >
                    </div>
                        <button type="button" id="toggleFormBtn" class="btn">+ Lisa uus kasutaja</button>
                </form>

                <?php if (isAdmin()): ?>
                    <div id="addForm" class="glass lisaInfoVorm formCard hidden">
                        <h2 class="formTitle dashboardItemTitle">Uue kasutaja lisamine</h2>

                        <form method="post" action="index.php?leht=adminUsers.php">
                            <div class="formGrid">

                                <div class="formGroup">
                                    <input
                                            type="text"
                                            name="kasutajanimi"
                                            placeholder="Kasutajanimi"
                                            required
                                    >
                                </div>

                                <div class="formGroup">
                                    <input
                                            type="email"
                                            name="epost"
                                            placeholder="E-post"
                                    >
                                </div>

                                <div class="formGroup">
                                    <input
                                            type="password"
                                            name="password"
                                            placeholder="Salasõna"
                                            required
                                    >
                                </div>

                                <div class="formGroup">
                                    <div class="customSelectWrapper">
                                        <select name="role" class="customSelectNative" required>
                                            <option value="0">Guest</option>
                                            <option value="1">Worker</option>
                                            <option value="2">Admin</option>
                                        </select>

                                        <button type="button" class="customSelectTrigger">
                                            <span class="customSelectText">Guest</span>
                                            <span class="customSelectArrow">⌄</span>
                                        </button>

                                        <div class="customSelectDropdown">
                                            <div class="customSelectOption selected" data-value="0">Guest</div>
                                            <div class="customSelectOption" data-value="1">Worker</div>
                                            <div class="customSelectOption" data-value="2">Admin</div>
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
                                <a href="?leht=adminUsers.php&sort=id&otsisona=<?= urlencode($otsisona) ?>">
                                    ID
                                </a>
                            </td>
                            <td>
                                <a href="?leht=adminUsers.php&sort=kasutajanimi&otsisona=<?= urlencode($otsisona) ?>">
                                    Kasutajanimi
                                </a>
                            </td>
                            <td>
                                <a href="?leht=adminUsers.php&sort=epost&otsisona=<?= urlencode($otsisona) ?>">
                                    E-post
                                </a>
                            </td>
                            <td>
                                <a href="?leht=adminUsers.php&sort=roll&otsisona=<?= urlencode($otsisona) ?>">
                                    Roll
                                </a>
                            </td>
                            <td>Tegevused</td>
                        </tr>
                        </thead>

                        <tbody id="piibudTableBody">
                        <?php if (!empty($usersNimekiri)): ?>
                            <?php foreach ($usersNimekiri as $user): ?>

                                <?php if (isset($_GET["muutmisid"]) && intval($_GET["muutmisid"]) === intval($user->Id) && isAdmin()): ?>
                                    <tr class="editRow">
                                        <td colspan="5">
                                            <form method="post"
                                                  action="index.php?leht=adminUsers.php&sort=<?= urlencode($sorttulp) ?>&otsisona=<?= urlencode($otsisona) ?>"
                                                  class="adminEditForm">

                                                <input type="hidden" name="muudetudid" value="<?= htmlspecialchars($user->Id) ?>">

                                                <div class="formGrid">
                                                    <div class="formGroup">
                                                        <input
                                                                type="text"
                                                                name="kasutajanimi"
                                                                value="<?= htmlspecialchars($user->Kasutajanimi) ?>"
                                                                placeholder="Kasutajanimi"
                                                                required
                                                        >
                                                    </div>

                                                    <div class="formGroup">
                                                        <input
                                                                type="email"
                                                                name="epost"
                                                                value="<?= htmlspecialchars($user->Epost) ?>"
                                                                placeholder="E-post"
                                                        >
                                                    </div>

                                                    <div class="formGroup">
                                                        <div class="customSelectWrapper">
                                                            <select name="role" class="customSelectNative" required>
                                                                <option value="0" <?= ($user->Roll == 0 ? 'selected' : '') ?>>Guest</option>
                                                                <option value="1" <?= ($user->Roll == 1 ? 'selected' : '') ?>>Worker</option>
                                                                <option value="2" <?= ($user->Roll == 2 ? 'selected' : '') ?>>Admin</option>
                                                            </select>

                                                            <button type="button" class="customSelectTrigger">
                                                                <span class="customSelectText">
                                                                    <?= ($user->Roll == 0 ? 'Guest' : ($user->Roll == 1 ? 'Worker' : 'Admin')) ?>
                                                                </span>
                                                                <span class="customSelectArrow">⌄</span>
                                                            </button>

                                                            <div class="customSelectDropdown">
                                                                <div class="customSelectOption <?= ($user->Roll == 0 ? 'selected' : '') ?>" data-value="0">Guest</div>
                                                                <div class="customSelectOption <?= ($user->Roll == 1 ? 'selected' : '') ?>" data-value="1">Worker</div>
                                                                <div class="customSelectOption <?= ($user->Roll == 2 ? 'selected' : '') ?>" data-value="2">Admin</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="formActions">
                                                    <button type="submit" name="muutmine" class="btn">Muuda</button>
                                                    <a href="index.php?leht=adminUsers.php&sort=<?= urlencode($sorttulp) ?>&otsisona=<?= urlencode($otsisona) ?>" class="btn katkestaBtn">
                                                        Katkesta
                                                    </a>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <tr>
                                        <td><?= htmlspecialchars($user->Id) ?></td>
                                        <td><?= htmlspecialchars($user->Kasutajanimi) ?></td>
                                        <td><?= htmlspecialchars($user->Epost) ?></td>
                                        <td><?= htmlspecialchars($user->Roll) ?></td>
                                        <td>
                                            <a href="index.php?leht=adminUsers.php&muutmisid=<?= urlencode($user->Id) ?>&sort=<?= urlencode($sorttulp) ?>&otsisona=<?= urlencode($otsisona) ?>" class="tableActionBtn muudaBtn">
                                                Muuda
                                            </a>

                                            <?php if (isAdmin()): ?>
                                                <a href="index.php?leht=adminUsers.php&kustutaid=<?= urlencode($user->Id) ?>"
                                                   onclick="return confirm('Kas kustutada see kasutaja?')" class="tableActionBtn kustutaBtn">
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
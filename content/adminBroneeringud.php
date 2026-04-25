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

$stats = getBronStats();

$todayCount = $stats["todayCount"];
$tomorrowCount = $stats["tomorrowCount"];
$todayGuests = $stats["todayGuests"];
$nextTime = $stats["nextTime"];
$futureCount = $stats["futureCount"];
$allCount = $stats["allCount"];

if ($nextTime === "-") {
    $nextTime = "Puudub";
}

/* Значения по умолчанию */
$sorttulp = "nimi";
$otsisona = "";
$vaade = "koik";

/* Сортировка */
if (isset($_GET["sort"])) {
    $sorttulp = $_REQUEST["sort"];
}

/* Поиск */
if (isset($_GET["otsisona"])) {
    $otsisona = $_REQUEST["otsisona"];
}

if (isset($_GET["vaade"])) {
    $vaade = $_REQUEST["vaade"];
}

/* Добавление новой записи — только admin */
if (isset($_POST["lisa"]) && isWorker()) {
    $kliendiNimi = htmlspecialchars(trim($_POST["kliendiNimi"] ?? ""));
    $kontakt = htmlspecialchars(trim($_POST["kontakt"] ?? ""));
    $kuupaev = trim($_POST["kuupaev"] ?? "");
    $kellaaeg = trim($_POST["kellaaeg"] ?? "");
    $kulalisteArv = (int)($_POST["selectKulalisteArv"] ?? 0);

    if ($kliendiNimi !== "" && $kuupaev !== "" && $kellaaeg !== "" && $kulalisteArv > 0) {
        $salvestatavKuupaev = date("Y-m-d", strtotime($kuupaev));
        $salvestatavKellaaeg = date("H:i:s", strtotime($kellaaeg));

        lisaBron($kliendiNimi, $salvestatavKuupaev, $salvestatavKellaaeg, $kulalisteArv, $kontakt);
    }

    header("Location: index.php?leht=adminBroneeringud.php");
    exit();
}

$bronNimekiri = kysiBron($sorttulp, $otsisona, $vaade);

/* Изменение */
if (isset($_POST["muutmine"]) && isAdmin()) {
    $muudetudid = (int)($_POST["muudetudid"] ?? 0);
    $kliendiNimi = htmlspecialchars(trim($_POST["kliendiNimi"] ?? ""));
    $kontakt = htmlspecialchars(trim($_POST["kontakt"] ?? ""));
    $kuupaev = trim($_POST["kuupaev"] ?? "");
    $kellaaeg = trim($_POST["kellaaeg"] ?? "");
    $kulalisteArv = (int)($_POST["selectKulalisteArv"] ?? 0);

    if ($muudetudid > 0 && $kliendiNimi !== "" && $kuupaev !== "" && $kellaaeg !== "" && $kulalisteArv > 0) {
        $salvestatavKuupaev = date("Y-m-d", strtotime($kuupaev));
        $salvestatavKellaaeg = date("H:i:s", strtotime($kellaaeg));

        muudaBron($muudetudid, $kliendiNimi, $salvestatavKuupaev, $salvestatavKellaaeg, $kulalisteArv, $kontakt);
    }

    header("Location: index.php?leht=adminBroneeringud.php&sort=" . urlencode($sorttulp) . "&otsisona=" . urlencode($otsisona) . "&vaade=" . urlencode($vaade));
    exit();
}

/* Удаление — только admin */
if (isset($_GET["kustutaid"]) && isAdmin()) {
    kustutaBron((int)$_GET["kustutaid"]);
    header("Location: index.php?leht=adminBroneeringud.php&sort=" . urlencode($sorttulp) . "&otsisona=" . urlencode($otsisona) . "&vaade=" . urlencode($vaade));
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
                    <a href="index.php?leht=adminBroneeringud.php" class="adminNavLink active">Broneeringud</a>
                    <a href="index.php?leht=adminUsers.php" class="adminNavLink">Kasutajad</a>
                    <a href="index.php?leht=adminGraafik.php" class="adminNavLink">Graafik</a>
                </nav>
            <?php else: ?>
                <nav class="adminNav">
                    <a href="index.php?leht=adminPiibud.php" class="adminNavLink">Piibud</a>
                    <a href="index.php?leht=adminJoogid.php" class="adminNavLink">Joogid</a>
                    <a href="index.php?leht=adminSnakid.php" class="adminNavLink">Toidud</a>
                    <a href="index.php?leht=adminBroneeringud.php" class="adminNavLink active">Broneeringud</a>
                    <a href="index.php?leht=adminGraafik.php" class="adminNavLink">Graafik</a>
                </nav>
            <?php endif; ?>
            <div class="adminSidebarBottom">
                <a href="?leht=logout.php" class="adminLogout">Logout</a>
            </div>
        </aside>

        <div class="adminContent">
            <div class="menuuHeader">
                <h1 class="menuuTitle">BRONEERINGUD</h1>
                <div class="menuuTitleLine"></div>
            </div>

            <div class="dashboardInfo">
                <p class="dashboardDiscription">
                    <?php if (isAdmin()): ?>
                        Broneeringute vaatamine, lisamine, muutmine ja kustutamine. <!-- ИЗМЕНЕНО -->
                    <?php else: ?>
                        Vaata broneeringud. <!-- ИЗМЕНЕНО -->
                    <?php endif; ?>
                </p>
            </div>
            <div class="miniStatistika">

                <a href="index.php?leht=adminBroneeringud.php&vaade=tana&sort=<?= urlencode($sorttulp) ?>&otsisona=<?= urlencode($otsisona) ?>"
                   class="statCard glass <?= $vaade == 'tana' ? 'active' : '' ?>">
                    <div class="statCardInfo">
                        <div class="statLabel">Täna</div>
                        <div class="statValue"><?= htmlspecialchars($todayCount) ?></div>
                        <div class="statSubtext">broneeringut</div>
                    </div>
                </a>

                <a href="index.php?leht=adminBroneeringud.php&vaade=homme&sort=<?= urlencode($sorttulp) ?>&otsisona=<?= urlencode($otsisona) ?>"
                   class="statCard glass <?= $vaade == 'homme' ? 'active' : '' ?>">
                    <div class="statCardInfo">
                        <div class="statLabel">Homme</div>
                        <div class="statValue"><?= htmlspecialchars($tomorrowCount) ?></div>
                        <div class="statSubtext">broneeringut</div>
                    </div>
                </a>

                <a href="index.php?leht=adminBroneeringud.php&vaade=tulevased&sort=<?= urlencode($sorttulp) ?>&otsisona=<?= urlencode($otsisona) ?>"
                   class="statCard glass <?= $vaade == 'tulevased' ? 'active' : '' ?>">
                    <div class="statCardInfo">
                        <div class="statLabel">Tulevased</div>
                        <div class="statValue"><?= htmlspecialchars($futureCount) ?></div>
                        <div class="statSubtext">broneeringut</div>
                    </div>
                </a>

                <a href="index.php?leht=adminBroneeringud.php&vaade=koik&sort=<?= urlencode($sorttulp) ?>&otsisona=<?= urlencode($otsisona) ?>"
                   class="statCard glass <?= $vaade == 'koik' ? 'active' : '' ?>">
                    <div class="statCardInfo">
                        <div class="statLabel">Kõik</div>
                        <div class="statValue"><?= htmlspecialchars($allCount) ?></div>
                        <div class="statSubtext">broneeringut</div>
                    </div>
                </a>

                <div class="statCard glass infoCard">
                    <div class="statCardInfo">
                        <div class="statLabel">Külalisi täna</div>
                        <div class="statValue"><?= htmlspecialchars($todayGuests) ?></div>
                        <div class="statSubtext">külalist</div>
                    </div>
                </div>

                <div class="statCard glass infoCard">
                    <div class="statCardInfo">
                        <div class="statLabel">Järgmine</div>
                        <div class="statValue"><?= htmlspecialchars($nextTime) ?></div>
                        <div class="statSubtext">täna</div>
                    </div>
                </div>

            </div>

            <div class="adminPanelCard">

                <form method="get" action="index.php" class="adminTtoolbar">
                    <input type="hidden" name="leht" value="adminBroneeringud.php">
                    <input type="hidden" name="sort" value="<?= htmlspecialchars($sorttulp) ?>">
                    <input type="hidden" name="vaade" value="<?= htmlspecialchars($vaade) ?>">

                    <div class="adminOtsingBox">
                        <input
                            type="text"
                            name="otsisona"
                            class="adminPiibuOtsing"
                            placeholder="Otsi broneering..."
                            value="<?= htmlspecialchars($otsisona) ?>"
                        >
                    </div>

                    <?php if (isWorker()): ?>
                        <button type="button" id="toggleFormBtn" class="btn">+ Lisa uus broneering</button>
                    <?php endif; ?>
                </form>

                <?php if (isWorker()): ?>
                    <div id="addForm" class="glass lisaInfoVorm formCard hidden">
                        <h2 class="formTitle dashboardItemTitle">Uue broneeringu lisamine</h2>

                        <form method="post" action="index.php?leht=adminBroneeringud.php">
                            <div class="formGrid">

                                <div class="formGroup">
                                    <input
                                        type="text"
                                        name="kliendiNimi"
                                        placeholder="Kliendi nimi"
                                        value="<?= htmlspecialchars($_POST["kliendiNimi"] ?? "") ?>"
                                        required
                                    >
                                </div>

                                <div class="formGroup">
                                    <input
                                        type="text"
                                        name="kontakt"
                                        placeholder="Kontakt"
                                        value="<?= htmlspecialchars($_POST["kontakt"] ?? "") ?>"
                                    >
                                </div>

                                <div class="formGroup">
                                    <input
                                            type="text"
                                            name="kuupaev"
                                            class="bronKuupaev"
                                            placeholder="Vali kuupäev"
                                            value="<?= htmlspecialchars($_POST["kuupaev"] ?? "") ?>"
                                            required
                                    >
                                </div>

                                <div class="formGroup">
                                    <input
                                            type="text"
                                            name="kellaaeg"
                                            class="bronKellaaeg"
                                            placeholder="Vali kellaaeg"
                                            value="<?= htmlspecialchars($_POST["kellaaeg"] ?? "") ?>"
                                            required
                                    >
                                </div>
                                <div class="formGroup">
                                    <input
                                            type="number"
                                            name="selectKulalisteArv"
                                            min="1"
                                            placeholder="Külaliste arv"
                                            value="<?= htmlspecialchars($_POST["selectKulalisteArv"] ?? "") ?>"
                                            required
                                    >
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
                                <a href="?leht=adminBroneeringud.php&sort=id&otsisona=<?= urlencode($otsisona) ?>">ID</a>
                            </td>
                            <td>
                                <a href="?leht=adminBroneeringud.php&sort=nimi&otsisona=<?= urlencode($otsisona) ?>">Nimi</a>
                            </td>
                            <td>
                                <a href="?leht=adminBroneeringud.php&sort=kuupaev&otsisona=<?= urlencode($otsisona) ?>">Kuupäev</a>
                            </td>
                            <td>
                                <a href="?leht=adminBroneeringud.php&sort=kell&otsisona=<?= urlencode($otsisona) ?>">Kell</a>
                            </td>
                            <td>
                                <a href="?leht=adminBroneeringud.php&sort=inimestearv&otsisona=<?= urlencode($otsisona) ?>">Inimeste arv</a>
                            </td>
                            <td>
                                <a href="?leht=adminBroneeringud.php&sort=kontakt&otsisona=<?= urlencode($otsisona) ?>">Kontakt</a>
                            </td>
                            <td>Tegevused</td>
                        </tr>
                        </thead>

                        <tbody id="piibudTableBody">
                        <?php if (!empty($bronNimekiri)): ?>
                            <?php foreach ($bronNimekiri as $bron): ?>

                                <?php if (isset($_GET["muutmisid"]) && intval($_GET["muutmisid"]) === intval($bron->Id) && isAdmin()): ?>
                                    <tr class="editRow">
                                        <td colspan="7">
                                            <form method="post"
                                                  action="index.php?leht=adminBroneeringud.php&sort=<?= urlencode($sorttulp) ?>&otsisona=<?= urlencode($otsisona) ?>&vaade=<?= urlencode($vaade) ?>"                                                  class="adminEditForm">

                                                <input type="hidden" name="muudetudid" value="<?= htmlspecialchars($bron->Id) ?>">

                                                <div class="formGrid">

                                                    <div class="formGroup">
                                                        <input
                                                                type="text"
                                                                name="kliendiNimi"
                                                                value="<?= htmlspecialchars($bron->Name) ?>"
                                                                placeholder="Kliendi nimi"
                                                                required
                                                        >
                                                    </div>

                                                    <div class="formGroup">
                                                        <input
                                                                type="text"
                                                                name="kontakt"
                                                                value="<?= htmlspecialchars($bron->Contact ?? '') ?>"
                                                                placeholder="Kontakt"
                                                        >
                                                    </div>

                                                    <div class="formGroup">
                                                        <input
                                                                type="text"
                                                                name="kuupaev"
                                                                class="bronKuupaev"
                                                                placeholder="Vali kuupäev"
                                                                value="<?= htmlspecialchars(date("d.m.Y", strtotime($bron->Date))) ?>"
                                                                required
                                                        >
                                                    </div>

                                                    <div class="formGroup">
                                                        <input
                                                                type="text"
                                                                name="kellaaeg"
                                                                class="bronKellaaeg"
                                                                placeholder="Vali kellaaeg"
                                                                value="<?= htmlspecialchars(substr($bron->Time, 0, 5)) ?>"
                                                                required
                                                        >
                                                    </div>

                                                    <div class="formGroup">
                                                        <input
                                                                type="number"
                                                                name="selectKulalisteArv"
                                                                min="1"
                                                                value="<?= htmlspecialchars($bron->PeopleCount) ?>"
                                                                placeholder="Külaliste arv"
                                                                required
                                                        >
                                                    </div>
                                                </div>

                                                <div class="formActions">
                                                    <button type="submit" name="muutmine" class="btn">Muuda</button>
                                                    <a href="index.php?leht=adminBroneeringud.php&sort=<?= urlencode($sorttulp) ?>&otsisona=<?= urlencode($otsisona) ?>&vaade=<?= urlencode($vaade) ?>" class="btn katkestaBtn">
                                                        Katkesta
                                                    </a>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <tr>
                                        <td><?= htmlspecialchars($bron->Id) ?></td>
                                        <td><?= htmlspecialchars($bron->Name) ?></td>
                                        <td><?= htmlspecialchars($bron->Date) ?></td>
                                        <td><?= htmlspecialchars(substr($bron->Time, 0, 5)) ?></td> <!-- ИЗМЕНЕНО -->
                                        <td><?= htmlspecialchars($bron->PeopleCount) ?></td>
                                        <td><?= htmlspecialchars($bron->Contact ?? '-') ?></td>
                                        <td>
                                            <?php if (isAdmin()): ?>
                                                <a href="index.php?leht=adminBroneeringud.php&muutmisid=<?= urlencode($bron->Id) ?>&sort=<?= urlencode($sorttulp) ?>&otsisona=<?= urlencode($otsisona) ?>&vaade=<?= urlencode($vaade) ?>" class="tableActionBtn muudaBtn">
                                                    Muuda
                                                </a>

                                                <a href="index.php?leht=adminBroneeringud.php&kustutaid=<?= urlencode($bron->Id) ?>&sort=<?= urlencode($sorttulp) ?>&otsisona=<?= urlencode($otsisona) ?>&vaade=<?= urlencode($vaade) ?>"
                                                   onclick="return confirm('Kas kustutada see broneering?')" class="tableActionBtn kustutaBtn">
                                                    Kustuta
                                                </a>
                                            <?php else: ?>
                                                <span>-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>

                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7">Tulemusi ei leitud.</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        flatpickr(".bronKuupaev", {
            dateFormat: "d.m.Y",
            minDate: "today",
            locale: "et"
        });

        flatpickr(".bronKellaaeg", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            minuteIncrement: 30,
            locale: "et"
        });
    });
</script>
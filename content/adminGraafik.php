<?php
include('config.php');
session_start();
require_once("functions.php");

global $yhendus;

if (!isset($_SESSION['role']) || $_SESSION['role'] < 1) {
    header("Location: ?leht=login.php");
    exit();
}

$kuu = $_GET["kuu"] ?? date("Y-m");
$tootaja = $_GET["tootaja"] ?? "";

if (!isAdmin()) {
    $tootaja = $_SESSION["username"];
}

$tootajad = kysiTootajad();

if (isset($_GET["kustutaid"]) && isAdmin()) {
    kustutaGraafik((int)$_GET["kustutaid"]);

    header("Location: index.php?leht=adminGraafik.php&kuu=" . urlencode($kuu) . "&tootaja=" . urlencode($tootaja));
    exit();
}

if (isset($_POST["lisa"]) && isAdmin()) {
    $userName = htmlspecialchars(trim($_POST["UserName"] ?? ""));
    $date = trim($_POST["Date"] ?? "");
    $startTime = trim($_POST["StartTime"] ?? "");
    $endTime = trim($_POST["EndTime"] ?? "");

    if ($userName !== "" && $date !== "" && $startTime !== "" && $endTime !== "") {
        $salvestatavDate = date("Y-m-d", strtotime($date));
        $salvestatavStart = date("H:i:s", strtotime($startTime));
        $salvestatavEnd = date("H:i:s", strtotime($endTime));

        lisaGraafik($userName, $salvestatavDate, $salvestatavStart, $salvestatavEnd);
    }

    header("Location: index.php?leht=adminGraafik.php&kuu=" . urlencode($kuu) . "&tootaja=" . urlencode($tootaja));
    exit();
}

if (isset($_POST["muutmine"])) {
    $id = (int)($_POST["muudetudid"] ?? 0);
    $vanaVahetus = kysiGraafikYks($id);

    $saabMuuta = false;

    if ($vanaVahetus) {
        if (isAdmin()) {
            $saabMuuta = true;
        } elseif ($vanaVahetus["UserName"] === ($_SESSION["username"] ?? "")) {
            $saabMuuta = true;
        }
    }

    if ($saabMuuta) {
        $userName = isAdmin()
                ? htmlspecialchars(trim($_POST["UserName"] ?? ""))
                : $_SESSION["username"];

        $date = trim($_POST["Date"] ?? "");
        $startTime = trim($_POST["StartTime"] ?? "");
        $endTime = trim($_POST["EndTime"] ?? "");

        if ($id > 0 && $userName !== "" && $date !== "" && $startTime !== "" && $endTime !== "") {
            $salvestatavDate = date("Y-m-d", strtotime($date));
            $salvestatavStart = date("H:i:s", strtotime($startTime));
            $salvestatavEnd = date("H:i:s", strtotime($endTime));

            muudaGraafik($id, $userName, $salvestatavDate, $salvestatavStart, $salvestatavEnd);
        }
    }

    header("Location: index.php?leht=adminGraafik.php&kuu=" . urlencode($kuu) . "&tootaja=" . urlencode($tootaja));
    exit();
}

$graafikNimekiri = kysiGraafikKuu($kuu, $tootaja);

$graafikPaevadeKaupa = [];

foreach ($graafikNimekiri as $rida) {
    $paev = date("j", strtotime($rida["Date"]));
    $graafikPaevadeKaupa[$paev][] = $rida;
}

$kuuAlgus = $kuu . "-01";
$paevadeArv = date("t", strtotime($kuuAlgus));
$esimesePaevaNadalapaev = date("N", strtotime($kuuAlgus));

$muudetavVahetus = null;

if (isset($_GET["muutmisid"])) {
    $muudetavVahetus = kysiGraafikYks((int)$_GET["muutmisid"]);
}

$lisaKuupaev = $_GET["kuupaev"] ?? date("Y-m-d");
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
                    <a href="index.php?leht=adminUsers.php" class="adminNavLink">Kasutajad</a>
                    <a href="index.php?leht=adminGraafik.php" class="adminNavLink active">Graafik</a>
                </nav>
            <?php else: ?>
                <nav class="adminNav">
                    <a href="index.php?leht=adminPiibud.php" class="adminNavLink">Piibud</a>
                    <a href="index.php?leht=adminJoogid.php" class="adminNavLink">Joogid</a>
                    <a href="index.php?leht=adminSnakid.php" class="adminNavLink">Toidud</a>
                    <a href="index.php?leht=adminBroneeringud.php" class="adminNavLink">Broneeringud</a>
                    <a href="index.php?leht=adminGraafik.php" class="adminNavLink active">Graafik</a>
                </nav>
            <?php endif; ?>

            <div class="adminSidebarBottom">
                <a href="?leht=logout.php" class="adminLogout">Logout</a>
            </div>
        </aside>

        <div class="adminContent">

            <div class="menuuHeader">
                <h1 class="menuuTitle">GRAAFIK</h1>
                <div class="menuuTitleLine"></div>
            </div>

            <div class="dashboardInfo">
                <p class="dashboardDiscription">
                    <?php if (isAdmin()): ?>
                        Graafikute vaatamine, lisamine, muutmine ja kustutamine.
                    <?php else: ?>
                        Vaata ja muuda oma graafikut.
                    <?php endif; ?>
                </p>
            </div>

            <div class="adminPanelCard">

                <form method="get" action="index.php" class="adminTtoolbar">
                    <input type="hidden" name="leht" value="adminGraafik.php">

                    <div class="adminOtsingBox">
                        <input
                                type="month"
                                name="kuu"
                                class="adminPiibuOtsing"
                                value="<?= htmlspecialchars($kuu) ?>"
                        >
                    </div>

                    <?php if (isWorker()): ?>
                        <div class="adminOtsingBox">
                            <select name="tootaja" class="adminPiibuOtsing">
                                <option value="">Kõik töötajad</option>

                                <?php foreach ($tootajad as $t): ?>
                                    <option value="<?= htmlspecialchars($t["UserName"]) ?>"
                                            <?= $tootaja == $t["UserName"] ? "selected" : "" ?>>
                                        <?= htmlspecialchars($t["UserName"]) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
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
                    <?php endif; ?>

                    <button type="submit" class="btn">Näita</button>

                    <?php if (isWorker()): ?>
                        <a href="index.php?leht=adminGraafik.php&lisa=1&kuu=<?= urlencode($kuu) ?>&tootaja=<?= urlencode($tootaja) ?>" class="btn">
                            + Lisa vahetus
                        </a>
                    <?php endif; ?>
                </form>

                <?php if (isset($_GET["lisa"]) && isWorker()): ?>
                    <div class="glass lisaInfoVorm formCard">
                        <h2 class="formTitle dashboardItemTitle">Uue vahetuse lisamine</h2>

                        <form method="post" action="index.php?leht=adminGraafik.php&kuu=<?= urlencode($kuu) ?>&tootaja=<?= urlencode($tootaja) ?>">
                            <div class="formGrid">

                                <div class="formGroup">
                                    <select name="UserName" required>
                                        <option value="">Vali töötaja</option>

                                        <?php foreach ($tootajad as $t): ?>
                                            <option value="<?= htmlspecialchars($t["UserName"]) ?>">
                                                <?= htmlspecialchars($t["UserName"]) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="formGroup">
                                    <input
                                            type="text"
                                            name="Date"
                                            class="bronKuupaev"
                                            placeholder="Vali kuupäev"
                                            value="<?= htmlspecialchars(date("d.m.Y", strtotime($lisaKuupaev))) ?>"
                                            required
                                    >
                                </div>

                                <div class="formGroup">
                                    <input
                                            type="text"
                                            name="StartTime"
                                            class="bronKellaaeg"
                                            placeholder="Algus"
                                            required
                                    >
                                </div>

                                <div class="formGroup">
                                    <input
                                            type="text"
                                            name="EndTime"
                                            class="bronKellaaeg"
                                            placeholder="Lõpp"
                                            required
                                    >
                                </div>

                            </div>

                            <div class="formActions">
                                <button type="submit" name="lisa" class="btn">Salvesta</button>

                                <a href="index.php?leht=adminGraafik.php&kuu=<?= urlencode($kuu) ?>&tootaja=<?= urlencode($tootaja) ?>" class="btn katkestaBtn">
                                    Katkesta
                                </a>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>

                <?php if ($muudetavVahetus): ?>
                    <?php
                    $saabMuutaSeda = isAdmin() || $muudetavVahetus["UserName"] === ($_SESSION["username"] ?? "");
                    ?>

                    <?php if ($saabMuutaSeda): ?>
                        <div class="glass lisaInfoVorm formCard">
                            <h2 class="formTitle dashboardItemTitle">Vahetuse muutmine</h2>

                            <form method="post" action="index.php?leht=adminGraafik.php&kuu=<?= urlencode($kuu) ?>&tootaja=<?= urlencode($tootaja) ?>">
                                <input type="hidden" name="muudetudid" value="<?= htmlspecialchars($muudetavVahetus["Id"]) ?>">

                                <div class="formGrid">

                                    <?php if (isAdmin()): ?>
                                        <div class="formGroup">
                                            <select name="UserName" required>
                                                <?php foreach ($tootajad as $t): ?>
                                                    <option value="<?= htmlspecialchars($t["UserName"]) ?>"
                                                            <?= $muudetavVahetus["UserName"] == $t["UserName"] ? "selected" : "" ?>>
                                                        <?= htmlspecialchars($t["UserName"]) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    <?php endif; ?>

                                    <div class="formGroup">
                                        <input
                                                type="text"
                                                name="Date"
                                                class="bronKuupaev"
                                                value="<?= htmlspecialchars(date("d.m.Y", strtotime($muudetavVahetus["Date"]))) ?>"
                                                required
                                        >
                                    </div>

                                    <div class="formGroup">
                                        <input
                                                type="text"
                                                name="StartTime"
                                                class="bronKellaaeg"
                                                value="<?= htmlspecialchars(substr($muudetavVahetus["StartTime"], 0, 5)) ?>"
                                                required
                                        >
                                    </div>

                                    <div class="formGroup">
                                        <input
                                                type="text"
                                                name="EndTime"
                                                class="bronKellaaeg"
                                                value="<?= htmlspecialchars(substr($muudetavVahetus["EndTime"], 0, 5)) ?>"
                                                required
                                        >
                                    </div>

                                </div>

                                <div class="formActions">
                                    <button type="submit" name="muutmine" class="btn">Muuda</button>

                                    <a href="index.php?leht=adminGraafik.php&kuu=<?= urlencode($kuu) ?>&tootaja=<?= urlencode($tootaja) ?>" class="btn katkestaBtn">
                                        Katkesta
                                    </a>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <div class="adminTabeliAlus graafikCalendarBox">
                    <table class="graafikCalendar">
                        <thead>
                        <tr>
                            <td>ESMASPÄEV</td>
                            <td>TEISIPÄEV</td>
                            <td>KOLMAPÄEV</td>
                            <td>NELJAPÄEV</td>
                            <td>REEDE</td>
                            <td>LAUPÄEV</td>
                            <td>PÜHAPÄEV</td>
                        </tr>
                        </thead>

                        <tbody>
                        <tr>
                            <?php
                            for ($tyhi = 1; $tyhi < $esimesePaevaNadalapaev; $tyhi++) {
                                echo "<td class='calendarDay emptyDay'></td>";
                            }

                            $paevNadalas = $esimesePaevaNadalapaev;

                            for ($paev = 1; $paev <= $paevadeArv; $paev++) {
                                echo "<td class='calendarDay'>";

                                echo "<div class='dayNumber'>" . $paev . "</div>";

                                if (isset($graafikPaevadeKaupa[$paev])) {
                                    foreach ($graafikPaevadeKaupa[$paev] as $vahetus) {
                                        $saabMuutaVahetust = isAdmin() || $vahetus["UserName"] === ($_SESSION["username"] ?? "");

                                        echo "<div class='shiftItem'>";

                                        echo "<strong>" . htmlspecialchars($vahetus["UserName"]) . "</strong>";

                                        echo "<span>";
                                        echo htmlspecialchars(substr($vahetus["StartTime"], 0, 5));
                                        echo " - ";
                                        echo htmlspecialchars(substr($vahetus["EndTime"], 0, 5));
                                        echo "</span>";

                                        if ($saabMuutaVahetust) {
                                            echo "<a class='shiftEdit' href='index.php?leht=adminGraafik.php&muutmisid=" . urlencode($vahetus["Id"]) . "&kuu=" . urlencode($kuu) . "&tootaja=" . urlencode($tootaja) . "'>Muuda</a>";
                                        }

                                        if (isAdmin()) {
                                            echo "<a class='shiftDelete' onclick=\"return confirm('Kas kustutada see vahetus?')\" href='index.php?leht=adminGraafik.php&kustutaid=" . urlencode($vahetus["Id"]) . "&kuu=" . urlencode($kuu) . "&tootaja=" . urlencode($tootaja) . "'>Kustuta</a>";
                                        }

                                        echo "</div>";
                                    }
                                }

                                if (isAdmin()) {
                                    $kuupaevLisa = $kuu . "-" . str_pad($paev, 2, "0", STR_PAD_LEFT);

                                    echo "<a class='addShiftSmall' href='index.php?leht=adminGraafik.php&lisa=1&kuupaev=" . urlencode($kuupaevLisa) . "&kuu=" . urlencode($kuu) . "&tootaja=" . urlencode($tootaja) . "'>+ Lisa</a>";
                                }

                                echo "</td>";

                                if ($paevNadalas == 7) {
                                    echo "</tr><tr>";
                                    $paevNadalas = 1;
                                } else {
                                    $paevNadalas++;
                                }
                            }
                            ?>
                        </tr>
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
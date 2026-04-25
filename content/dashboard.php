<?php
session_start();
require_once("functions.php");

if (!isLoggedIn()) {
    header("Location: ?leht=login.php");
    exit();
}

/* Текущая страница из GET-параметра */
$currentPage = $_GET['leht'] ?? 'dashboard.php';

/* Разделы админ-панели */
if (isAdmin()) {
    $adminSections = [
            [
                    "title" => "Piibud",
                    "description" => "Halda vesipiipude andmebaasi, maitseid ja hindu.",
                    "link" => "?leht=adminPiibud.php",
                    "icon" => "💨"
            ],
            [
                    "title" => "Joogid",
                    "description" => "Muuda jookide nimekirja, kirjeldusi ja hindu.",
                    "link" => "?leht=adminJoogid.php",
                    "icon" => "🍹"
            ],
            [
                    "title" => "Toidud",
                    "description" => "Halda toitude ja snäkkide andmebaasi.",
                    "link" => "?leht=adminSnakid.php",
                    "icon" => "🍽"
            ],
            [
                    "title" => "Broneeringud",
                    "description" => "Vaata, muuda või kustuta klientide broneeringuid.",
                    "link" => "?leht=adminBroneeringud.php",
                    "icon" => "📅"
            ],
            [
                    "title" => "Kasutajad",
                    "description" => "Halda kasutajate andmeid ja rolle.",
                    "link" => "?leht=adminUsers.php",
                    "icon" => "👤"
            ],
            [
                    "title" => "Graafik",
                    "description" => "Koosta ja muuda töötajate töögraafikut.",
                    "link" => "?leht=admin_graafik.php",
                    "icon" => "🕒"
            ]
    ];
} elseif (isWorker()) {
    $adminSections = [
            [
                    "title" => "Piibud",
                    "description" => "Halda vesipiipude andmebaasi, maitseid ja hindu.",
                    "link" => "?leht=adminPiibud.php",
                    "icon" => "💨"
            ],
            [
                    "title" => "Joogid",
                    "description" => "Muuda jookide nimekirja, kirjeldusi ja hindu.",
                    "link" => "?leht=adminJoogid.php",
                    "icon" => "🍹"
            ],
            [
                    "title" => "Toidud",
                    "description" => "Halda toitude ja snäkkide andmebaasi.",
                    "link" => "?leht=adminSnakid.php",
                    "icon" => "🍽"
            ],
            [
                    "title" => "Broneeringud",
                    "description" => "Vaata, muuda või kustuta klientide broneeringuid.",
                    "link" => "?leht=adminBroneeringud.php",
                    "icon" => "📅"
            ],
            [
                    "title" => "Graafik",
                    "description" => "Koosta ja muuda töötajate töögraafikut.",
                    "link" => "?leht=admin_graafik.php",
                    "icon" => "🕒"
            ]
    ];
} elseif (isGuest()) {
    $adminSections = [
            [
                    "title" => "Minu broneeringud",
                    "description" => "Vaata oma aktiivseid ja varasemaid broneeringuid.",
                    "link" => "?leht=minu_broneeringud.php",
                    "icon" => "📅"
            ],
            [
                    "title" => "Minu andmed",
                    "description" => "Vaata ja muuda oma kasutajakonto andmeid.",
                    "link" => "?leht=minu_andmed.php",
                    "icon" => "👤"
            ]
    ];
} else {
    header("Location: ?leht=login.php");
    exit();
}
?>

<section class="dashboardPage avaleht">
    <div class="categoryContainer">
        <div class="menuuHeader">
            <h1 class="menuuTitle">JUHTPANEEL</h1>
            <div class="menuuTitleLine"></div>
        </div>
        <div class="dashboardInfo">
            <p class="dashboardDiscription">
                Vali, millist andmebaasi või sektsiooni soovid hallata.
            </p>
        </div>
        <div class="glass categoryList">
            <div class="piibuCardTop">
                <div class="piibuInfo">

                </div>
            </div>
            <div class="dasboardCards">

                <?php
                foreach ($adminSections as $section): ?>
                    <div class="piibuCard">
                        <a class="dashboardItem" href="<?= htmlspecialchars($section["link"]) ?>">
                            <div class="dashboardContent">
                                <h3 class="dashboardItemTitle"><?= htmlspecialchars($section["title"]) ?></h3>
                                <p class="dashboardItemDescription"><?= htmlspecialchars($section["description"]) ?></p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php
/* Текущая страница из GET-параметра */
$currentPage = $_GET['leht'] ?? 'admin.php';

/* Разделы админ-панели */
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
        "link" => "?leht=admin_joogid.php",
        "icon" => "🍹"
    ],
    [
        "title" => "Toidud",
        "description" => "Halda toitude ja snäkkide andmebaasi.",
        "link" => "?leht=admin_toidud.php",
        "icon" => "🍽"
    ],
    [
        "title" => "Broneeringud",
        "description" => "Vaata, muuda või kustuta klientide broneeringuid.",
        "link" => "?leht=admin_bron.php",
        "icon" => "📅"
    ],
    [
        "title" => "Kasutajad",
        "description" => "Halda kasutajate andmeid ja rolle.",
        "link" => "?leht=admin_users.php",
        "icon" => "👤"
    ],
    [
        "title" => "Graafik",
        "description" => "Koosta ja muuda töötajate töögraafikut.",
        "link" => "?leht=admin_graafik.php",
        "icon" => "🕒"
    ]
];
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
                <?php foreach ($adminSections as $section): ?>
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
<?php
$frituur = [
        [
                "id" => 1,
                "name" => "Fritüür",
                "description" => "Krõbedad ja maitsvad snäkid, mis sobivad ideaalselt jagamiseks.",
                "items" => [
                        [
                                "id" => 101,
                                "name" => "Friikartulid",
                                "description" => "Kuldsed ja krõbedad friikartulid.",
                                "price" => 4
                        ],
                        [
                                "id" => 102,
                                "name" => "Juustupulgad",
                                "description" => "Paneeritud pulgad sulava juustuga.",
                                "price" => 5
                        ],
                        [
                                "id" => 103,
                                "name" => "Kana nuggetsid",
                                "description" => "Krõbedad kanatükid klassikalises paneeringus.",
                                "price" => 6
                        ],
                        [
                                "id" => 104,
                                "name" => "Sibularõngad",
                                "description" => "Mahlased sibularõngad krõbeda kattega.",
                                "price" => 5
                        ],
                        [
                                "id" => 105,
                                "name" => "Kana tiivad",
                                "description" => "Vürtsikad ja mahlased kanatiivad.",
                                "price" => 7
                        ],
                        [
                                "id" => 106,
                                "name" => "Jalapeno poppers",
                                "description" => "Kergelt vürtsikad paprikad juustutäidisega.",
                                "price" => 6
                        ]
                ]
        ]
];
?>

<section class="categoryPage snakiPage">
    <div class="categoryContainer">
        <div class="menuuHeader">
            <h1 class="menuuTitle">MENÜÜ</h1>
            <div class="menuuTitleLine"></div>
        </div>

        <?php include("menuuTabs.php"); ?>

        <div class="glass categoryList">
            <?php foreach($frituur as $fri): ?>
                <div class="categoryCard">
                    <div class="piibuCardTop">
                        <div class="piibuInfo">
                            <h3 class="piibuName"><?= htmlspecialchars($fri["name"]) ?></h3>
                            <p class="piibuDescription"><?= htmlspecialchars($fri["description"]) ?></p>
                        </div>
                    </div>

                    <details class="maitsedBox">
                        <summary class="btn-link maitsedToggle">Vaata valikut</summary>

                        <div class="maitsedList">
                            <?php foreach ($fri["items"] as $item): ?>
                                <div class="maitseItem">
                                    <div class="piibuPriceRow">
                                        <h4 class="piibuName maitseTitle"><?= htmlspecialchars($item["name"]) ?></h4>
                                        <p class="maitseDesc"><?= htmlspecialchars($item["description"]) ?></p>

                                    </div>
                                    <div class="piibuPriceRow">
                                        <span class="piibuPriceLabel">Hind -</span>
                                        <span class="piibuPriceValue"><?= htmlspecialchars($item["price"]) ?> €</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </details>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
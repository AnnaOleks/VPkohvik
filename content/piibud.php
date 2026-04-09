<?php
$piibud = [
        [
                "id" => 1,
                "name" => "Šerbet",
                "description" => "Kerge ja meeldiv valik magusamate ja marjasemate maitsetega.",
                "full_price" => 22,
                "client_price" => 19,
                "flavors" => [
                        [
                                "id" => 101,
                                "name" => "Blueberry Mint",
                                "description" => "Magus mustikas koos värske piparmündiga."
                        ],
                        [
                                "id" => 102,
                                "name" => "Grape Mint",
                                "description" => "Viinamarjane maitse värske jahedusega."
                        ],
                        [
                                "id" => 103,
                                "name" => "Love 66",
                                "description" => "Puuviljane, magus ja kergelt jahutav klassikaline segu."
                        ],
                        [
                                "id" => 104,
                                "name" => "Strawberry",
                                "description" => "Pehme maasikamaitse, magus ja kerge."
                        ]
                ]
        ],
        [
                "id" => 2,
                "name" => "Premium",
                "description" => "Tugevam ja sügavam valik neile, kes soovivad intensiivsemat maitset.",
                "full_price" => 26,
                "client_price" => 23,
                "flavors" => [
                        [
                                "id" => 201,
                                "name" => "Dark Berry",
                                "description" => "Sügav marjane maitse, rikkalik ja täidlasem."
                        ],
                        [
                                "id" => 202,
                                "name" => "Ice Citrus",
                                "description" => "Tsitruseline, särtsakas ja tugevalt jahutav."
                        ],
                        [
                                "id" => 203,
                                "name" => "Cola Lemon",
                                "description" => "Magus-hapukas segu koola ja sidruni nootidega."
                        ],
                        [
                                "id" => 204,
                                "name" => "Black Tea",
                                "description" => "Tume ja veidi vürtsikas tee nootidega segu."
                        ]
                ]
        ]
];
?>



<section class="categoryPage piibuPage">
    <div class="categoryContainer">
        <div class="menuuHeader">
            <h1 class="menuuTitle">MENÜÜ</h1>
            <div class="menuuTitleLine"></div>
        </div>
        <?php include("menuuTabs.php"); ?>
        <div class="glass categoryList">
            <?php foreach($piibud as $piip): ?>
                <div class="categoryCard">
                    <div class="piibuCardTop">
                        <div class="piibuInfo">
                            <h3 class="piibuName"><?= htmlspecialchars($piip["name"]) ?></h3>
                            <p class="piibuDescription"><?= htmlspecialchars($piip["description"]) ?></p>
                        </div>
                        <div class="piibuPrices">
                            <div class="piibuPriceRow">
                                <span class="piibuPriceLabel">Täishind -</span>
                                <span class="piibuPriceValue"><?= htmlspecialchars($piip["full_price"]) ?> €</span>
                            </div>

                            <div class="piibuPriceRow kliendiHind">
                                <span class="piibuPriceLabel">Kliendihind -</span>
                                <span class="piibuPriceValue"><?= htmlspecialchars($piip["client_price"]) ?> €</span>
                            </div>
                        </div>
                    </div>

                    <details class="maitsedBox">
                        <summary class="btn-link maitsedToggle">Vaata maitseid</summary>

                        <div class="maitsedList">
                            <?php foreach ($piip["flavors"] as $flavor): ?>
                                <div class="maitseItem">
                                    <h4 class="piibuName maitseTitle"><?= htmlspecialchars($flavor["name"]) ?></h4>
                                    <p class="maitseDesc"><?= htmlspecialchars($flavor["description"]) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </details>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
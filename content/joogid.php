<?php
$joogid = [
    [
        "id" => 1,
        "name" => "Kokteilid",
        "description" => "Maitseküllased kokteilid erinevatele eelistustele.",
        "subcategories" => [
            [
                "id" => 11,
                "name" => "Alkohoolsed kokteilid",
                "description" => "Klassikalised ja puuviljased alkohoolsed kokteilid.",
                "items" => [
                    [
                        "id" => 1101,
                        "name" => "Mojito",
                        "description" => "Värske laimi, piparmündi ja rummiga kokteil.",
                        "price" => 8
                    ],
                    [
                        "id" => 1102,
                        "name" => "Piña Colada",
                        "description" => "Troopiline kookose, ananassi ja rummiga kokteil.",
                        "price" => 9
                    ],
                    [
                        "id" => 1103,
                        "name" => "Sex on the Beach",
                        "description" => "Puuviljane ja magus klassikaline kokteil.",
                        "price" => 8
                    ],
                    [
                        "id" => 1104,
                        "name" => "Blue Lagoon",
                        "description" => "Erksa värvi tsitruseline alkohoolne kokteil.",
                        "price" => 8
                    ]
                ]
            ],
            [
                "id" => 12,
                "name" => "Alkoholivabad kokteilid",
                "description" => "Värskendavad ja mahlased alkoholivabad kokteilid.",
                "items" => [
                    [
                        "id" => 1201,
                        "name" => "Virgin Mojito",
                        "description" => "Laimi, piparmündi ja mulliveega värske alkoholivaba kokteil.",
                        "price" => 6
                    ],
                    [
                        "id" => 1202,
                        "name" => "Fruit Punch",
                        "description" => "Mitme puuvilja mahlane ja magus segu.",
                        "price" => 6
                    ],
                    [
                        "id" => 1203,
                        "name" => "Berry Lemonade",
                        "description" => "Marjane ja sidrunine värskendav jook.",
                        "price" => 6
                    ],
                    [
                        "id" => 1204,
                        "name" => "Passion Cooler",
                        "description" => "Eksootiline passionivilja maitsega jahutav kokteil.",
                        "price" => 6
                    ]
                ]
            ]
        ]
    ],
    [
        "id" => 2,
        "name" => "Alkohol",
        "description" => "Valik erinevaid alkohoolseid jooke.",
        "subcategories" => [
            [
                "id" => 21,
                "name" => "Kanged alkohoolsed joogid",
                "description" => "Tugevamad joogid, kus hind sõltub valitud margist.",
                "items" => [
                    [
                        "id" => 2101,
                        "name" => "Viski",
                        "description" => "Tugev ja sügava maitsega klassikaline jook.",
                        "brands" => [
                            [
                                "id" => 21011,
                                "name" => "Jameson",
                                "price" => 6
                            ],
                            [
                                "id" => 21012,
                                "name" => "Jack Daniel's",
                                "price" => 7
                            ],
                            [
                                "id" => 21013,
                                "name" => "Chivas Regal",
                                "price" => 8
                            ]
                        ]
                    ],
                    [
                        "id" => 2102,
                        "name" => "Viin",
                        "description" => "Puhas ja tugev traditsiooniline alkohol.",
                        "brands" => [
                            [
                                "id" => 21021,
                                "name" => "Saaremaa Vodka",
                                "price" => 5
                            ],
                            [
                                "id" => 21022,
                                "name" => "Absolut",
                                "price" => 6
                            ],
                            [
                                "id" => 21023,
                                "name" => "Grey Goose",
                                "price" => 8
                            ]
                        ]
                    ],
                    [
                        "id" => 2103,
                        "name" => "Rumm",
                        "description" => "Kergelt magus ja rikkaliku maitsega jook.",
                        "brands" => [
                            [
                                "id" => 21031,
                                "name" => "Bacardi",
                                "price" => 6
                            ],
                            [
                                "id" => 21032,
                                "name" => "Captain Morgan",
                                "price" => 6
                            ],
                            [
                                "id" => 21033,
                                "name" => "Havana Club",
                                "price" => 7
                            ]
                        ]
                    ],
                    [
                        "id" => 2104,
                        "name" => "Džinn",
                        "description" => "Aromaatne ja kergelt ürdine alkohol.",
                        "brands" => [
                            [
                                "id" => 21041,
                                "name" => "Gordon's",
                                "price" => 6
                            ],
                            [
                                "id" => 21042,
                                "name" => "Beefeater",
                                "price" => 7
                            ],
                            [
                                "id" => 21043,
                                "name" => "Bombay Sapphire",
                                "price" => 8
                            ]
                        ]
                    ]
                ]
            ],
            [
                "id" => 22,
                "name" => "Lahjem alkohol",
                "description" => "Kergemad alkohoolsed joogid mõnusaks nautimiseks.",
                "items" => [
                    [
                        "id" => 2201,
                        "name" => "Punane vein",
                        "description" => "Täidlasem ja pehme maitsega vein.",
                        "price" => 7
                    ],
                    [
                        "id" => 2202,
                        "name" => "Valge vein",
                        "description" => "Kergem ja värskem vein.",
                        "price" => 7
                    ],
                    [
                        "id" => 2203,
                        "name" => "Rosé vein",
                        "description" => "Õrn ja marjane vein.",
                        "price" => 7
                    ],
                    [
                        "id" => 2204,
                        "name" => "Siider",
                        "description" => "Kerge ja puuviljane alkohoolne jook.",
                        "price" => 5
                    ]
                ]
            ]
        ]
    ],
    [
        "id" => 3,
        "name" => "Karastusjoogid",
        "description" => "Karastavad joogid janu kustutamiseks.",
        "subcategories" => [
            [
                "id" => 31,
                "name" => "Gaseeritud joogid",
                "description" => "Klassikalised mulliga karastusjoogid.",
                "items" => [
                    [
                        "id" => 3101,
                        "name" => "Coca-Cola",
                        "description" => "Klassikaline gaseeritud karastusjook.",
                        "price" => 3
                    ],
                    [
                        "id" => 3102,
                        "name" => "Fanta",
                        "description" => "Magus apelsinimaitseline jook.",
                        "price" => 3
                    ],
                    [
                        "id" => 3103,
                        "name" => "Sprite",
                        "description" => "Värske sidruni-lubja maitsega jook.",
                        "price" => 3
                    ],
                    [
                        "id" => 3104,
                        "name" => "Tonic",
                        "description" => "Kergelt mõrkjas ja värskendav gaseeritud jook.",
                        "price" => 3
                    ]
                ]
            ],
            [
                "id" => 32,
                "name" => "Mahlad ja vesi",
                "description" => "Kergemad ja naturaalsemad joogid.",
                "items" => [
                    [
                        "id" => 3201,
                        "name" => "Apelsinimahl",
                        "description" => "Värske ja mahlane tsitruseline jook.",
                        "price" => 3
                    ],
                    [
                        "id" => 3202,
                        "name" => "Õunamahl",
                        "description" => "Magus-hapukas klassikaline mahl.",
                        "price" => 3
                    ],
                    [
                        "id" => 3203,
                        "name" => "Gaseerimata vesi",
                        "description" => "Puhas ja kerge joogivesi.",
                        "price" => 2
                    ],
                    [
                        "id" => 3204,
                        "name" => "Gaseeritud vesi",
                        "description" => "Mulliga värskendav vesi.",
                        "price" => 2
                    ]
                ]
            ]
        ]
    ],
    [
        "id" => 4,
        "name" => "Tee",
        "description" => "Soojad ja rahustavad teed erinevatele maitsetele.",
        "subcategories" => [
            [
                "id" => 41,
                "name" => "Must tee",
                "description" => "Tugevamad ja klassikalised teed.",
                "items" => [
                    [
                        "id" => 4101,
                        "name" => "English Breakfast",
                        "description" => "Tugev ja klassikaline must tee.",
                        "price" => 3
                    ],
                    [
                        "id" => 4102,
                        "name" => "Earl Grey",
                        "description" => "Bergamotiaroomiga must tee.",
                        "price" => 3
                    ]
                ]
            ],
            [
                "id" => 42,
                "name" => "Roheline tee",
                "description" => "Kergemad ja värskemad teevalikud.",
                "items" => [
                    [
                        "id" => 4201,
                        "name" => "Jasmine Green Tea",
                        "description" => "Õrn rohelise tee ja jasmiini kooslus.",
                        "price" => 3
                    ],
                    [
                        "id" => 4202,
                        "name" => "Sencha",
                        "description" => "Kerge ja puhta maitsega roheline tee.",
                        "price" => 3
                    ]
                ]
            ],
            [
                "id" => 43,
                "name" => "Taimetee",
                "description" => "Rahustavad ja aromaatsed taimeteed.",
                "items" => [
                    [
                        "id" => 4301,
                        "name" => "Kummel",
                        "description" => "Pehme ja rahustav kummelitee.",
                        "price" => 3
                    ],
                    [
                        "id" => 4302,
                        "name" => "Piparmünt",
                        "description" => "Värske ja kergelt jahutav piparmünditee.",
                        "price" => 3
                    ]
                ]
            ]
        ]
    ],
    [
        "id" => 5,
        "name" => "Kohv",
        "description" => "Klassikalised kohvijoogid igaks hetkeks.",
        "subcategories" => [
            [
                "id" => 51,
                "name" => "Must kohv",
                "description" => "Tugevad ja puhtad kohvivalikud.",
                "items" => [
                    [
                        "id" => 5101,
                        "name" => "Espresso",
                        "description" => "Väike, tugev ja aromaatne kohv.",
                        "price" => 2.5
                    ],
                    [
                        "id" => 5102,
                        "name" => "Americano",
                        "description" => "Espresso kuuma veega, mahedam ja pikem kohv.",
                        "price" => 3
                    ]
                ]
            ],
            [
                "id" => 52,
                "name" => "Piimakohvid",
                "description" => "Pehmemad ja kreemisemad kohvijoogid.",
                "items" => [
                    [
                        "id" => 5201,
                        "name" => "Cappuccino",
                        "description" => "Tasakaalus espresso ja piimavahuga kohv.",
                        "price" => 3.5
                    ],
                    [
                        "id" => 5202,
                        "name" => "Latte",
                        "description" => "Pehme ja piimane kohvijook.",
                        "price" => 4
                    ],
                    [
                        "id" => 5203,
                        "name" => "Flat White",
                        "description" => "Tugevama kohvimaitsega siidine piimakohv.",
                        "price" => 4
                    ]
                ]
            ]
        ]
    ]
];
?>

<section class="categoryPage joogiPage">
    <div class="categoryContainer">
        <div class="menuuHeader">
            <h1 class="menuuTitle">MENÜÜ</h1>
            <div class="menuuTitleLine"></div>
        </div>

        <?php include("menuuTabs.php"); ?>

        <div class="glass categoryList">
            <?php foreach($joogid as $jook): ?>
                <div class="categoryCard">
                    <div class="piibuCardTop">
                        <div class="piibuInfo">
                            <h3 class="piibuName"><?= htmlspecialchars($jook["name"]) ?></h3>
                            <p class="piibuDescription"><?= htmlspecialchars($jook["description"]) ?></p>
                        </div>
                    </div>

                    <details class="maitsedBox">
                        <summary class="btn-link maitsedToggle">Vaata jooke</summary>

                        <div class="joogidList maitsedList">
                            <?php foreach ($jook["subcategories"] as $subcategory): ?>
                                <div class="maitseItem">
                                    <h4 class="piibuName maitseTitle"><?= htmlspecialchars($subcategory["name"]) ?></h4>
                                    <p class="maitseDesc"><?= htmlspecialchars($subcategory["description"]) ?></p>

                                    <div class="subcategoryItems">
                                        <?php foreach ($subcategory["items"] as $item): ?>
                                            <div class="maitseItem">
                                                <h4 class="piibuName maitseTitle"><?= htmlspecialchars($item["name"]) ?></h4>
                                                <p class="maitseDesc"><?= htmlspecialchars($item["description"]) ?></p>

                                                <?php if (isset($item["brands"])): ?>
                                                    <div class="joogiBrands">
                                                        <?php foreach ($item["brands"] as $brand): ?>
                                                            <div class="piibuPriceRow">
                                                                <span class="piibuPriceLabel">
                                                                    <?= htmlspecialchars($brand["name"]) ?> -
                                                                </span>
                                                                <span class="piibuPriceValue">
                                                                    <?= htmlspecialchars($brand["price"]) ?> €
                                                                </span>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="piibuPriceRow">
                                                        <span class="piibuPriceLabel">Hind -</span>
                                                        <span class="piibuPriceValue">
                                                            <?= htmlspecialchars($item["price"]) ?> €
                                                        </span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
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

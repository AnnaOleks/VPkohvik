<?php
include('config.php');
session_start();
require_once("functions.php");

$piibud = kysiPiibudMenuu();
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
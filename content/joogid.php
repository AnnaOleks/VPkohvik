<?php
include('config.php');
session_start();
require_once("functions.php");

$joogid = kysiJoogidMenuu();
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

                            <p class="piibuDescription">
                                <?= htmlspecialchars($jook["description"]) ?>
                            </p>
                        </div>
                    </div>

                    <details class="maitsedBox">
                        <summary class="btn-link maitsedToggle">Vaata jooke</summary>

                        <div class="joogidList maitsedList">
                            <?php foreach ($jook["items"] as $item): ?>
                                <div class="maitseItem">
                                    <h4 class="piibuName maitseTitle">
                                        <?= htmlspecialchars($item["name"]) ?>
                                    </h4>

                                    <p class="maitseDesc">
                                        <?= htmlspecialchars($item["description"]) ?>
                                    </p>

                                    <div class="piibuPriceRow">
                                        <span class="piibuPriceLabel">Hind -</span>
                                        <span class="piibuPriceValue">
                                            <?= htmlspecialchars(number_format($item["price"], 2)) ?> €
                                        </span>
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
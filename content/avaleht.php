<?php

include('config.php');
session_start();
require_once("functions.php");

global $yhendus;
global $kasutajanimi;

/* Сообщения */
$bronViga = "";
$bronSuccess = "";

if (isset($_POST["btnBroneeri"])) {
    $kliendiNimi = htmlspecialchars(trim($_POST["kliendiNimi"] ?? ""));
    $kontakt = htmlspecialchars(trim($_POST["kontakt"] ?? ""));
    $kuupaev = trim($_POST["kuupaev"] ?? "");
    $kellaaeg = trim($_POST["kellaaeg"] ?? "");
    $kulalisteArv = (int)($_POST["selectKulalisteArv"] ?? 0);

    if (
            $kliendiNimi === "" ||
            $kontakt === "" ||
            $kuupaev === "" ||
            $kellaaeg === "" ||
            $kulalisteArv <= 0
    ) {
        $bronViga = "Palun täida kõik väljad.";
    } else {
        $salvestatavKuupaev = date("Y-m-d", strtotime($kuupaev));
        $salvestatavKellaaeg = date("H:i:s", strtotime($kellaaeg));

        lisaBron($kliendiNimi, $salvestatavKuupaev, $salvestatavKellaaeg, $kulalisteArv, $kontakt);

        $bronSuccess = "Broneering on edukalt lisatud!";

        $_POST = [];
    }
}
?>

<section class="avaleht">
    <?php if ($bronViga !== ""): ?>
        <div class="toast toastError" id="toastMessage">
            <span><?= htmlspecialchars($bronViga) ?></span>
            <button type="button" class="toastClose" onclick="closeToast()">×</button>
        </div>
    <?php endif; ?>

    <?php if ($bronSuccess !== ""): ?>
        <div class="toast toastSuccess" id="toastMessage">
            <span><?= htmlspecialchars($bronSuccess) ?></span>
            <button type="button" class="toastClose" onclick="closeToast()">×</button>
        </div>
    <?php endif; ?>
    <!-- Первый экран -->
    <div class="avaleht1Ekraan">
        <div class="vasakBlock">
            <div class="avalehtBrand">
                <h1 class="avalehtVP">VP</h1>
                <h3 class="avalehtKohvik">KOHVIK</h3>
            </div>
            <p class="avalehtSlogan">Suits. Rahu. Aeg iseendale</p>
            <div class="avalehtActions">
                <input type="submit" name="btnBroneeri" value="BRONEERI" class="btn">
                <a href="?leht=piibud.php" class="btn-link">
                    <span>AVA MENÜÜ</span>
                    <span>›</span>
                </a>
            </div>
        </div>
        <div class="paremBlock"></div>
    </div>
    <div class="glass avaleht2Ekraan">
        <div class="bronBlock">
            <h3>Broneeri laud</h3>
            <form method="post" action="index.php?leht=avaleht.php" id="bronForm">
                <div class="avalehtBronForm row1">
                    <input type="text" name="kliendiNimi" id="kliendiNimi" placeholder="Nimi ja perekonnanimi" value="<?= htmlspecialchars($_POST["kliendiNimi"] ?? "") ?>">
                    <input type="text" name="kontakt" id="kontakt" placeholder="Telefon / e-mail" value="<?= htmlspecialchars($_POST["kontakt"] ?? "") ?>">
                </div>
                <div class="avalehtBronForm row2">
                    <input type="text" name="kuupaev" id="kuupaev" placeholder="Vali kuupäev" value="<?= htmlspecialchars($_POST["kuupaev"] ?? "") ?>">
                    <input type="text" name="kellaaeg" id="kellaaeg" placeholder="Vali kellaaeg" value="<?= htmlspecialchars($_POST["kellaaeg"] ?? "") ?>">
                    <div class="customSelectWrapper">
                        <select name="selectKulalisteArv" class="customSelectNative">
                            <option value="" disabled <?= empty($_POST["selectKulalisteArv"]) ? 'selected' : '' ?>>Vali külastajate arv</option>
                            <option value="1" <?= (($_POST["selectKulalisteArv"] ?? "") == "1") ? 'selected' : '' ?>>1</option>
                            <option value="2" <?= (($_POST["selectKulalisteArv"] ?? "") == "2") ? 'selected' : '' ?>>2</option>
                            <option value="3" <?= (($_POST["selectKulalisteArv"] ?? "") == "3") ? 'selected' : '' ?>>3</option>
                            <option value="4" <?= (($_POST["selectKulalisteArv"] ?? "") == "4") ? 'selected' : '' ?>>4</option>
                            <option value="5" <?= (($_POST["selectKulalisteArv"] ?? "") == "5") ? 'selected' : '' ?>>5</option>
                            <option value="6" <?= (($_POST["selectKulalisteArv"] ?? "") == "6") ? 'selected' : '' ?>>6</option>
                            <option value="7" <?= (($_POST["selectKulalisteArv"] ?? "") == "7") ? 'selected' : '' ?>>7</option>
                            <option value="8" <?= (($_POST["selectKulalisteArv"] ?? "") == "8") ? 'selected' : '' ?>>8+</option>
                        </select>

                        <button type="button" class="customSelectTrigger">
                            <span class="customSelectText">
                                <?=!empty($_POST["selectKulalisteArv"])
                                        ? htmlspecialchars($_POST["selectKulalisteArv"])
                                        : "Vali külastajate arv"
                                ?>
                            </span>
                            <span class="customSelectArrow">⌄</span>
                        </button>

                        <div class="customSelectDropdown">
                            <div class="customSelectOption" data-value="1">1</div>
                            <div class="customSelectOption" data-value="2">2</div>
                            <div class="customSelectOption" data-value="3">3</div>
                            <div class="customSelectOption" data-value="4">4</div>
                            <div class="customSelectOption" data-value="5">5</div>
                            <div class="customSelectOption" data-value="6">6</div>
                            <div class="customSelectOption" data-value="7">7</div>
                            <div class="customSelectOption" data-value="8">8+</div>
                        </div>
                    </div>
                </div>
                <input type="submit" name="btnBroneeri" class="btn" value="BRONEERI">
            </form>
        </div>
        <div class="piibudBlock">
            <h3>Meie vesipiibu tubakad</h3>
            <div class="piibudCards">
                <div class="piibuCard">
                    <h4>Šerbetli</h4>
                    <p>Täishind - 22 €</p>
                    <p>Kliendihind - 19 €</p>
                </div>

                <div class="piibuCard">
                    <h4>Preemium</h4>
                    <p>Täishind - 26 €</p>
                    <p>Kliendihind - 22 €</p>
                </div>
            </div>
            <a href="?leht=piibud.php" class="btn-link">
                <span>VAATA PIIBUDE VALIKUT</span>
                <span>›</span>
            </a>
        </div>
    </div>
</section>

<script>
    function closeToast() {
        const toast = document.getElementById('toastMessage');
        if (toast) {
            toast.remove();
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        const toast = document.getElementById('toastMessage');

        if (toast) {
            setTimeout(() => {
                toast.style.transition = "opacity 0.4s ease, transform 0.4s ease";
                toast.style.opacity = "0";
                toast.style.transform = "translateY(-10px)";
                setTimeout(() => {
                    toast.remove();
                }, 400);
            }, 3500);
        }
    });
</script>
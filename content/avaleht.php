<?php
function clearVarsExcept($url, $varname) {
    $url = basename($url);
    if (str_starts_with($url, "?")) {
        return "?$varname=".$_REQUEST[$varname];
    }
    return strtok($url, "?")."?$varname=".$_REQUEST[$varname];
}
?>

<section class="avaleht">
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
                <a href="?leht=menuu.php" class="btn-link">
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
            <div class="avalehtBronForm">
                <input type="text" name="kuupaev" id="kuupaev" placeholder="Vali kuupäev">
                <input type="text" name="kellaaeg" id="kellaaeg" placeholder="Vali kellaaeg">
            </div>
            <div class="avalehtBronForm">
                <div class="customSelectWrapper">
                    <select name="selectKulalisteArv" class="customSelectNative">
                        <option value="" selected disabled>Vali külastajate arv</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8+</option>
                    </select>

                    <button type="button" class="customSelectTrigger">
                        <span class="customSelectText">Vali külastajate arv</span>
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
            <a href="piibud.php" class="btn-link">
                <span>VAATA PIIBUDE VALIKUT</span>
                <span>›</span>
            </a>
        </div>
    </div>
</section>

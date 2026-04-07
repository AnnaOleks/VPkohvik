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
                <a href="menuu.php" class="btn-link">
                    <span>AVA MENÜÜ</span>
                    <span>›</span>
                </a>
            </div>
        </div>
        <div class="paremBlock"></div>
    </div>
    <div class="avaleht2Ekraan">
        <div class="glass bronBlock">
            <h3>Broneeri laud</h3>
            <div class="avalehtBronForm">
                <input type="date" name="kuupaev">
                <input type="time" name="kellaaeg">
            </div>
            <div class="avalehtBronForm">
                <select name="selectKulalisteArv">
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
            </div>
            <input type="submit" name="btnBroneeri" value="BRONEERI">
        </div>
        <div class="glass piibudBlock">
            <h3>Piibud</h3>
            <div class="avalehtPiipHind">
                <h4>Šerbetli</h4>
                <p>Täishind - 24 €</p>
                <p>Kliendihind - 19 €</p>
            </div>
            <div class="avalehtPiipHind">
                <h4>Darkside</h4>
                <p>Täishind - 26 €</p>
                <p>Kliendihind - 22 €</p>
            </div>
            <input type="submit" name="btnPiibuMenuu" value="Vaata piibude valikut >" onclick="location.href='piibud.php'">
        </div>
    </div>
</section>

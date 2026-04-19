<?php
$currentPage = $_GET['leht'] ?? 'login.php';
?>

<section class="categoryPage loginPage avaleht">
    <div class="categoryContainer">
        <div class="menuuHeader">
            <h1 class="menuuTitle">REGISTREERIMINE</h1>
            <div class="menuuTitleLine"></div>
        </div>
        <div class="menuuTabs">
            <a class="menuuTab <?= $currentPage === 'login.php' ? 'active' : '' ?>" href="?leht=login.php">
                <span class="menuCategoryName">SISSELOGIMINE</span>
                <span class="menuCategoryArrow">›</span>
            </a>

            <a class="menuuTab <?= $currentPage === 'registr.php' ? 'active' : '' ?>" href="?leht=registr.php">
                <span class="menuCategoryName">REGISTREERIMINE</span>
                <span class="menuCategoryArrow">›</span>
            </a>
        </div>
        <div class="glass categoryList loginContainer ">
            <div>
                <input type="text" id="login" name="login" required placeholder="Kasutajanimi">
                <input type="email" id="email" name="email" required placeholder="E-post">
                <input type="password" id="pass" name="pass" required placeholder="Parool">
            </div>
            <input type="submit" name="btnLogin" value="REGISTREERI" class="btn btnLogi">
            <div class="avalehtActions">
                <a href="?leht=login.php" class="btn-link btn-loginPage">
                    <span>Konto on juba olemas, logi sisse</span>
                    <span>›</span>
                </a>
            </div>
        </div>
    </div>
</section>

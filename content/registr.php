<?php
$currentPage = $_GET['leht'] ?? 'login.php';

$regViga = "";
$regSuccess = "";

if (isset($_POST["btnRegister"])) {
    $UserName = htmlspecialchars(trim($_POST["UserName"] ?? ""));
    $Email = htmlspecialchars(trim($_POST["Email"] ?? ""));
    $Password = trim($_POST["Password"] ?? "");

    if (
            $UserName === "" ||
            $Email === "" ||
            $Password === ""
    ) {
        $regViga = "Palun täida kõik väljad.";
    } else {
        lisaKlient($UserName, $Email, $Password);
        $regSuccess = "Oled edukalt registreeritud!";
        $_POST = [];
    }
}
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

        <form method="post" action="" class="glass categoryList loginContainer">
            <?php if (!empty($regViga)): ?>
                <div class="errorMsg"><?= htmlspecialchars($regViga) ?></div>
            <?php endif; ?>

            <?php if (!empty($regSuccess)): ?>
                <div class="successMsg"><?= htmlspecialchars($regSuccess) ?></div>
            <?php endif; ?>

            <div>
                <input type="text" id="UserName" name="UserName" required placeholder="Kasutajanimi">
                <input type="email" id="Email" name="Email" required placeholder="E-post">
                <input type="password" id="Password" name="Password" required placeholder="Parool">
            </div>

            <input type="submit" name="btnRegister" value="REGISTREERI" class="btn btnLogi">

            <div class="avalehtActions">
                <a href="?leht=login.php" class="btn-link btn-loginPage">
                    <span>Konto on juba olemas, logi sisse</span>
                    <span>›</span>
                </a>
            </div>
        </form>
    </div>
</section>
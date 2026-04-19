<?php
include(__DIR__ . '/../config.php');

$currentPage = $_GET['leht'] ?? 'login.php';

require_once("functions.php");

global $yhendus;
global $isadmin;

if (!empty($_POST['login']) && !empty($_POST['password'])) {
    $login = htmlspecialchars(trim($_POST['login']));
    $pass = trim($_POST['password']);

    $paring = $yhendus->prepare("
        SELECT UserName, Email, Password, Role
        FROM Users
        WHERE UserName = ? OR Email = ?
    ");

    $paring->bind_param('ss', $login, $login);
    $paring->execute();
    $paring->bind_result($userName, $email, $passwordFromDb, $role);

    if ($paring->fetch()) {
        if (verifyAspNetIdentityPassword($pass, $passwordFromDb)) {
            if ($role == 2) {
                $_SESSION['Admin'] = true;
                $_SESSION['Worker'] = false;
                $_SESSION['Guest'] = false;
            } elseif ($role == 1) {
                $_SESSION['Admin'] = false;
                $_SESSION['Worker'] = true;
                $_SESSION['Guest'] = false;
            } else {
                $_SESSION['Admin'] = false;
                $_SESSION['Worker'] = false;
                $_SESSION['Guest'] = $userName;
            }

            $_SESSION['UserName'] = $userName;
            $_SESSION['Email'] = $email;

            echo "<script>window.location.href='index.php?leht=dashboard.php';</script>";
            exit();
        } else {
            echo "<script>alert('Parool on vale!');</script>";
        }
    } else {
        echo "<script>alert('Kasutajat ei leitud!');</script>";
    }

    $paring->close();
    $yhendus->close();
}
?>

<section class="categoryPage loginPage avaleht">
    <div class="categoryContainer">
        <div class="menuuHeader">
            <h1 class="menuuTitle">SISSELOGIMINE</h1>
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

        <form method="post" class="glass categoryList loginContainer">
            <div>
                <input type="text" id="login" name="login" required placeholder="E-post/Kasutajanimi">
                <input type="password" id="pass" name="password" required placeholder="Parool">
            </div>

            <input type="submit" name="btnLogin" value="LOGI SISSE" class="btn btnLogi">

            <div class="avalehtActions">
                <a href="?leht=piibud.php" class="btn-link btn-loginPage">
                    <span>Unustasid parooli?</span>
                </a>

                <a href="?leht=registr.php" class="btn-link btn-loginPage">
                    <span>Registreeri</span>
                    <span>›</span>
                </a>
            </div>
        </form>
    </div>
</section>
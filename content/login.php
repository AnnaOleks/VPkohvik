<?php
session_start();
include('config.php');
require_once("functions.php");

$currentPage = $_GET['leht'] ?? 'login.php';

global $yhendus;

$loginError = "";

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

            $_SESSION['role'] = (int)$role;
            $_SESSION['username'] = $userName;
            $_SESSION['email'] = $email;

            header("Location: ?leht=dashboard.php");
            exit();

        } else {
            $loginError = "Vale parool!";
        }

    } else {
        $loginError = "Kasutajat ei leitud!";
    }

    $paring->close();
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
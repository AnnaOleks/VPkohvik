<?php
include('config.php');
session_start();
require_once("functions.php");
?>
<nav>
    <ul class="navMenuu">
        <li>
            <a href="?leht=avaleht.php">Avaleht</a>
        </li>
        <li>
            <a href="?leht=piibud.php">Menüü</a>
            <ul class="dropdownMenuu">
                <li>
                    <a href="?leht=piibud.php">Piibud</a>
                </li>
                <li>
                    <a href="?leht=joogid.php">Joogid</a>
                </li>
                <li>
                    <a href="?leht=snakid.php">Snäkid</a>
                </li>
            </ul>
        </li>
        <li>
            <?php if (isAdmin()): ?>
                <!-- Если админ — показываем рабочий стол -->
                <a href="?leht=dashboard.php">
                    🛠 Juhtpaneel
                </a>
            <?php else: ?>
                <!-- Если не залогинен — показываем логин -->
                <a href="?leht=login.php">
                    <img src="img/login.png" class="login-icon" alt="Login"> Login
                </a>
            <?php endif; ?>
        </li>
    </ul>
</nav>

<?php
$currentPage = $_GET['leht'] ?? 'menuu.php';
?>

<div class="menuuTabs">
    <a class="menuuTab <?= $currentPage === 'piibud.php' ? 'active' : '' ?>" href="?leht=piibud.php">
        <span class="menuCategoryName">PIIBUD</span>
        <span class="menuCategoryArrow">›</span>
    </a>

    <a class="menuuTab <?= $currentPage === 'joogid.php' ? 'active' : '' ?>" href="?leht=joogid.php">
        <span class="menuCategoryName">JOOGID</span>
        <span class="menuCategoryArrow">›</span>
    </a>

    <a class="menuuTab <?= $currentPage === 'snakid.php' ? 'active' : '' ?>" href="?leht=snakid.php">
        <span class="menuCategoryName">SNÄKID</span>
        <span class="menuCategoryArrow">›</span>
    </a>
</div>
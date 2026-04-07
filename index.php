<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>VP Kohvik</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/styleNav.css">
    <link rel="stylesheet" href="style/styleHeader.css">
    <link rel="stylesheet" href="style/styleAvaleht.css">
</head>
<body>
<?php
include("header.php");
?>
<main>
    <?php
    if(isset($_GET["leht"])){
        include('content/'.$_GET["leht"]);
    } else {
        include('content/avaleht.php');
    }
    ?>
</main>
<?php
include("footer.php");
?>
</body>
</html>
<?php
ob_start();
session_start();
include('config.php');
require_once('functions.php');
?>

<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>VP Kohvik</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/styleNav.css">
    <link rel="stylesheet" href="style/styleHeader.css">
    <link rel="stylesheet" href="style/styleAvaleht.css">
    <link rel="stylesheet" href="style/styleMenuu.css">
    <link rel="stylesheet" href="style/stylePiibud.css">
    <link rel="stylesheet" href="style/styleJoogid.css">
    <link rel="stylesheet" href="style/styleSnakid.css">
    <link rel="stylesheet" href="style/styleLogin.css">
    <link rel="stylesheet" href="style/styleFooter.css">
    <link rel="stylesheet" href="style/styleRegistr.css">
    <link rel="stylesheet" href="style/styleDashboard.css">
    <link rel="stylesheet" href="style/styleAdminPiibud.css">
    <link rel="stylesheet" href="style/styleAdminJoogid.css">
    <link rel="stylesheet" href="style/styleAdminBroneeringud.css">
    <link rel="stylesheet" href="style/styleAdminGraafik.css">
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

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/et.js"></script>
<script>
    flatpickr("#kuupaev", {
        dateFormat: "d.m.Y",
        locale: "et",
        minDate: "today"
    });
    flatpickr("#kellaaeg", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const customSelects = document.querySelectorAll(".customSelectWrapper");

        customSelects.forEach(function (wrapper) {
            const nativeSelect = wrapper.querySelector(".customSelectNative");
            const trigger = wrapper.querySelector(".customSelectTrigger");
            const text = wrapper.querySelector(".customSelectText");
            const options = wrapper.querySelectorAll(".customSelectOption");

            if (!nativeSelect || !trigger || !text || !options.length) {
                return;
            }

            trigger.addEventListener("click", function (event) {
                event.stopPropagation();

                document.querySelectorAll(".customSelectWrapper").forEach(function (item) {
                    if (item !== wrapper) {
                        item.classList.remove("open");
                        item.classList.remove("open-up");
                    }
                });

                const rect = wrapper.getBoundingClientRect();
                const dropdownHeight = 220;
                const spaceBelow = window.innerHeight - rect.bottom;
                const spaceAbove = rect.top;

                wrapper.classList.remove("open-up");

                if (spaceBelow < dropdownHeight && spaceAbove > dropdownHeight) {
                    wrapper.classList.add("open-up");
                }

                wrapper.classList.toggle("open");
            });

            options.forEach(function (option) {
                option.addEventListener("click", function () {
                    const value = option.getAttribute("data-value");
                    const label = option.textContent;

                    nativeSelect.value = value;
                    text.textContent = label;

                    options.forEach(function (item) {
                        item.classList.remove("selected");
                    });

                    option.classList.add("selected");
                    wrapper.classList.remove("open");
                    wrapper.classList.remove("open-up");
                });
            });
        });

        document.addEventListener("click", function (event) {
            document.querySelectorAll(".customSelectWrapper").forEach(function (wrapper) {
                if (!wrapper.contains(event.target)) {
                    wrapper.classList.remove("open");
                    wrapper.classList.remove("open-up");
                }
            });
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const btn = document.getElementById("toggleFormBtn");
        const form = document.getElementById("addForm");

        if (!btn || !form) {
            return;
        }

        btn.addEventListener("click", function () {
            form.classList.toggle("hidden");

            if (form.classList.contains("hidden")) {
                btn.textContent = "+ Lisa uus piip";
            } else {
                btn.textContent = "− Peida vorm";
            }
        });
    });
</script>
</body>
</html>

<?php ob_end_flush(); ?>
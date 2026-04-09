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

            trigger.addEventListener("click", function () {

                document.querySelectorAll(".customSelectWrapper").forEach(function (item) {
                    if (item !== wrapper) {
                        item.classList.remove("open");
                        item.classList.remove("open-up");
                    }
                });

                const rect = wrapper.getBoundingClientRect();
                const dropdownHeight = 220; // как max-height
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
</body>
</html>
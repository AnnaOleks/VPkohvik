<?php
session_start();

/* Очищаем все данные сессии */
session_unset();

/* Уничтожаем сессию полностью */
session_destroy();

/* Перенаправляем пользователя на главную страницу */
header("Location: index.php?leht=avaleht.php");
exit();
?>
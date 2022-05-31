<?php

$conn = mysqli_connect("localhost", "bekjan", "bekjan123a)", "login");

if (!$conn) {
    echo "Подключение не удалось";
}
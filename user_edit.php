<?php
session_start();
require("functions.php");
$connection = new PDO("mysql:host=localhost; dbname=profiles; charset=utf8", "root", "");

$user_id = $_POST['id'];
$user_name = $_POST['name'];
$user_job = $_POST['job'];
$user_phone = $_POST['phone'];
$user_address = $_POST['address'];

if (edit_information($connection, $user_id, $user_job, $user_phone, $user_address, $user_name)) {
    set_flash_message("success", "Профиль успешно обновлен.");
    redirect_to("/tasks3/users.php");
} else {
    set_flash_message("danger", "Что-то пошло не так :(");
    redirect_to("/tasks3/users.php");
}
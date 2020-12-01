<?php
session_start();
require("functions.php");
$connection = new PDO("mysql:host=localhost; dbname=profiles; charset=utf8", "root", "");

$status = $_POST['status'];
$id = $_GET['id'];

$set_status_successful = set_status($connection, $id, $status);
if ($set_status_successful) {
    set_flash_message("success", "Профиль успешно обновлён.");
    redirect_to("/tasks3/page_profile.php?id={$id}");

} else {
    set_flash_message("danger", "Что-то пошло не так :(");
    redirect_to("/tasks3/page_profile.php?id={$id}");
}
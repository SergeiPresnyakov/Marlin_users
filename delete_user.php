<?php
session_start();
require("functions.php");
$connection = new PDO("mysql:host=localhost;dbname=profiles;charset=utf8", "root", "");

if (!isset($_SESSION['current_user'])) {
    redirect_to("page_login.php");
}

$id_to_delete = $_GET['id'];
$current_user_id = $_SESSION['current_user']['id'];

// если не админ и пытаешься редактировать не свой профиль
if ($_SESSION['current_user']['role'] != "admin" && !is_author($current_user_id, $id_to_delete)) {
    set_flash_message("warning", "Можно редактировать только свой профиль.");
    redirect_to("/tasks3/users.php");
}

delete_user($connection, $id_to_delete);

// если удалили свой аккаунт
if (is_author($current_user_id, $id_to_delete)) {
    redirect_to("logout.php");

// если не свой
} else {
    set_flash_message("warning", "Пользователь удален.");
    redirect_to("users.php");
}
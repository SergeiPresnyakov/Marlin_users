<?php
session_start();
require("functions.php");
$connection = new PDO("mysql:host=localhost;dbname=profiles;charset=utf8", "root", "");

$id = $_GET['id'];
$avatar = $_FILES['avatar'];

$avatar_successful_loaded = upload_avatar($connection, $id, $avatar);

if ($avatar_successful_loaded) {
    set_flash_message("success", "Профиль успешно обновлен.");
    redirect_to("page_profile.php?id={$id}");

} else {
    set_flash_message("danger", "Что-то пошло не так :(");
    redirect_to("page_profile.php?id={$id}");
}
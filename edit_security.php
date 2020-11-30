<?php
session_start();
require("functions.php");
$connection = new PDO("mysql:host=localhost; dbname=profiles; charset=utf8", "root", "");

$email = $_POST['email'];
$password = $_POST['password'];
$password_confirmation = $_POST['password_confirm'];

// ввел ли пользователь пароль повторно и правильно
$is_password_valid = $password === $password_confirmation;

/* 
проверка, существует ли уже такой email в базе
а если есть, наш ли это email
*/
$user_id = get_user_id_by_email($connection, $email);
$such_email_exists = !empty($user_id);
$is_not_our_email = $_POST['id'] != $user_id['id'];

// если такой email уже есть и он не наш
if ($such_email_exists && $is_not_our_email) {  
    set_flash_message("danger", "Этот email уже занят!");
    redirect_to("/tasks3/security.php?id={$_POST['id']}");  
}

// если не подтвердили пароль
if (!$is_password_valid) {
    set_flash_message("warning", "Подтвердите пароль!");
    redirect_to("/tasks3/security.php?id={$_POST['id']}");

// если всё в порядке
} else {
    edit_credentials($connection, $_POST['id'], $email, $password);
    set_flash_message("success", "Профиль успешно обновлён.");
    redirect_to("/tasks3/page_profile.php?id={$_POST['id']}");
}
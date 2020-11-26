<?php
session_start();
require("functions.php");
$connection = new PDO("mysql:host=localhost; dbname=profiles; charset=utf8", "root", "");


$user_id = get_user_id_by_email($connection, $_POST['email']);

/*
Если данный email уже занят,
перенаправляем обратно на форму добавления
и выводим сообщение
*/
if (!empty($user_id)) {
    set_flash_message("danger", "Данный email уже занят!");
    redirect_to("/tasks3/create_user.php");
}

$username = $_POST['name'];
$job = $_POST['job'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$status = $_POST['status'];
$vk = $_POST['vk'];
$telegram = $_POST['telegram'];
$instagram = $_POST['instagram'];
$email = $_POST['email'];
$password = $_POST['password'];
$user_id = add_user($connection, $email, $password);

edit_information($connection, $user_id, $job, $phone, $address, $username);
set_status($connection, $user_id, $status);
upload_avatar($connection, $user_id, $_FILE['avatar']);
add_social_links($connection, $user_id, $vk, $telegram, $instagram);

var_dump($_POST);
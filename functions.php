<?php

/*
    Parameters:
        PDO - $pdo
        string - $email
        string = $password
    Description: Добавить пользователя в БД
    Return value: int ($user_id)
*/
function add_user($pdo, $email, $password)
{
    $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
    $statement = $pdo->prepare($sql);
    $statement->execute(["email" => $email, "password" => $password]);

    $user_id = get_user_id_by_email($pdo, $email);
    return $user_id['id'];
}

/*
    Parameters:
        PDO - $pdo
        string - $email
    Description: Получить id пользователя по email
    Return value: array ["id" => id]
*/
function get_user_id_by_email($pdo, $email)
{
    $sql = "SELECT id FROM users WHERE email = :email";
    $statement = $pdo->prepare($sql);
    $statement->execute(["email" => $email]);
    $user_id = $statement->fetch(PDO::FETCH_ASSOC);

    return $user_id;
}

/*
    Parameters:
        string - $name
        string = $message
    Description: Подготовить флеш сообщение
    Return value: null
*/
function set_flash_message($name, $message)
{
    $_SESSION[$name] = $message;
}

/*
    Parameters:
        string - $name
    Description: Показать флеш сообщение
    Return value: null
*/
function display_flash_message($name)
{
    echo "<div class=\"alert alert-{$name} text-dark\" role=\"alert\">{$_SESSION[$name]}</div>";
    unset($_SESSION[$name]);
}

/*
    Parameters:
        string - $path
    Description: Перенаправляет на страницу по адресу $path
    Return value: null
*/
function redirect_to($path)
{
    header("Location: {$path}");
    exit;
}

/*
    Parameters:
        PDO - $pdo
        string - $email
        string - $password
    Description: Авторизация пользователя
    Return value: int
*/
function login($pdo, $email, $password)
{
    $sql = "SELECT id FROM users WHERE email = :email AND password = :password";
    $statement = $pdo->prepare($sql);
    $statement->execute(["email" => $email, "password" => $password]);
    $user_id = $statement->fetch(PDO::FETCH_ASSOC);

    return $user_id;
}

/*
    Parameters:
        PDO - $pdo
        int = $id
    Description: Получить все данные о пользователе
    Return value: array
*/
function get_user_data($pdo, $id)
{
    $sql = "SELECT * FROM users WHERE id = :id";
    $statement = $pdo->prepare($sql);
    $statement->execute(['id' => $id]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    return $user;
}

/*
    Parameters:
        PDO - $pdo
    Description: Получить данные всех пользователей
    Return value: array
*/
function get_all_users($pdo)
{
    $sql = "SELECT * FROM users";
    $statement = $pdo->prepare($sql);
    $statement->execute();
    $users = $statement->fetchAll(PDO::FETCH_ASSOC);

    return $users;
}

/*
    Parameters:
        PDO - $pdo
        int - $id
        string - $job
        string - $phone
        string - $address
        string = $username
    Description: редактировать общую информацию пользователя в базе
    Return value: boolean
*/
function edit_information($pdo, $id, $job, $phone, $address, $username)
{
    $sql = "UPDATE users SET job = :job, phone = :phone, address = :address, name = :username, role = :role WHERE id = :id";
    $statement = $pdo->prepare($sql);

    return $statement->execute([
        "job" => $job,
        "phone" => $phone,
        "address" => $address,
        "id" => $id,
        "username" => $username,
        "role" => "user"
        ]);    
}

/*
    Parameters:
        PDO - $pdo
        int - $id
        string - $status
    Description: установить статус пользователя
    Return value: boolean
*/
function set_status($pdo, $id, $status)
{
    $status_to_class = [
        "Онлайн" => "success",
        "Отошел" => "warning",
        "Не беспокоить" => "danger",
    ];

    $sql = "UPDATE users SET status = :status WHERE id = :id";
    $statement = $pdo->prepare($sql);

    return $statement->execute([
        "status" => $status_to_class[$status],
        "id" => $id
    ]);
}

/*
    Parameters:
        PDO - $pdo
        int - $id
        array - $file
    Description: загрузить аватар пользователя
    Return value: boolean
*/
function upload_avatar($pdo, $id, $file)
{
    $full_uploaddir = "D:/OSPanel/domains/divein/tasks3/img/demo/avatars/";
    $realative_uploaddir = "/tasks3/img/demo/avatars/";

    // полный адрес для загрузки файла
    $uploadfile = $full_uploaddir . basename($file['name']);
    

    $file_info = pathinfo($uploadfile);
    $filename = $file_info['filename']; // имя файла без расширения
    
    while (file_exists($uploadfile)) {
        // подбираем уникальное имя
        $filename = $filename . (string)random_int(0, 9);
        $uploadfile = $full_uploaddir . $filename . "." . $file_info['extension'];
    }

    move_uploaded_file($file['tmp_name'], $uploadfile);

    // относительный адрес для атрибута src аватарки
    $realative_uploadfile_src = $realative_uploaddir . $filename . "." . $file_info['extension'];

    $sql = "UPDATE users SET avatar = :avatar WHERE id = :id";
    $statement = $pdo->prepare($sql);

    return $statement->execute([
        "avatar" => $realative_uploadfile_src,
        "id" => $id
    ]);
}

/*
    Parameters:
        PDO - $pdo
        int - $id
        string - $vk
        string - $telegram
        string - $instagram
    Description: добавить ссылки на соцсети
    Return value: boolean
*/
function add_social_links($pdo, $id, $vk, $telegram, $instagram)
{
    $sql = "UPDATE users SET vk = :vk, telegram = :telegram, instagram = :instagram WHERE id = :id";
    $statement = $pdo->prepare($sql);

    return $statement->execute([
        "vk" => $vk,
        "telegram" => $telegram,
        "instagram" => $instagram,
        "id" => $id
    ]);
}

/*
    Parameters:
        int - $current_user_id
        int - $edit_user_id
    Description: Проверка свой ли профиль редактирует пользователь
    Return value: boolean
*/
function is_author($current_user_id, $edit_user_id)
{
    return $current_user_id == $edit_user_id;
}

/*
    Parameters:
        PDO - $pdo
        int - $user_id
    Description: Достать данные пользователя из базы по id
    Return value: array
*/
function get_user_by_id($pdo, $user_id)
{
    $sql = "SELECT * FROM users WHERE id = :id";
    $statement = $pdo->prepare($sql);
    $statement->execute(['id' => $user_id]);
    $user = $statement->fetchAll(PDO::FETCH_ASSOC);

    return $user[0];
}

/*
    Parameters:
        PDO - $pdo
        int - $id
        string - $email
        string - $password
    Description: Обновить email и пароль пользователя в базе
    Return value: boolean
*/
function edit_credentials($pdo, $id, $email, $password)
{
    $sql = "UPDATE users SET email = :email, password = :password WHERE id = :id";
    $statement = $pdo->prepare($sql);

    return $statement->execute([
        "email" => $email,
        "password" => $password,
        "id" => $id
    ]);
}

/*
    Parameters:
        PDO - $pdo
        int - $id
    Description: Удалить пользователя
    Return value: boolean
*/
function delete_user($pdo, $id)
{
    $sql = "DELETE FROM users WHERE id = :id";
    $statement = $pdo->prepare($sql);

    return $statement->execute(["id" => $id]);
}

/*
    Parameters:
            -
    Description: Выйти из системы
    Return value: null
*/
function logout()
{
    $_SESSION = array();
}
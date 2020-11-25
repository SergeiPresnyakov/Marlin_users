<?php

/*
    Parameters:
        PDO - $pdo
        string - $email
        string = $password

    Description: Добавить пользователя в БД

    Return value: null
*/
function add_user($pdo, $email, $password)
{
    $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
    $statement = $pdo->prepare($sql);
    $statement->execute(["email" => $email, "password" => $password]);
}

/*
    Parameters:
        PDO - $pdo
        string - $email

    Description: Получить id пользователя по email

    Return value: int (user_id)
*/
function get_user_id_by_email($pdo, $email)
{
    $sql = "SELECT id FROM users WHERE email = :email";
    $statement = $pdo->prepare($sql);
    $statement->execute(["email" => $email]);
    $user_id = $statement->fetchAll(PDO::FETCH_ASSOC);

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
        string = $id

    Description: вывести опции пользователя

    Return value: null
*/
function show_options($id)
{
    echo "<i class=\"fal fas fa-cog fa-fw d-inline-block ml-1 fs-md\"></i>
    <i class=\"fal fa-angle-down d-inline-block ml-1 fs-md\"></i>
    </a>
    <div class=\"dropdown-menu\">
    <a class=\"dropdown-item\" href=\"edit.php?id={$id}\">
        <i class=\"fa fa-edit\"></i>
    Редактировать</a>
    <a class=\"dropdown-item\" href=\"security.php?id={$id}\">
        <i class=\"fa fa-lock\"></i>
    Безопасность</a>
    <a class=\"dropdown-item\" href=\"status.php?id={$id}\">
        <i class=\"fa fa-sun\"></i>
    Установить статус</a>
    <a class=\"dropdown-item\" href=\"media.php?id={$id}\">
        <i class=\"fa fa-camera\"></i>
        Загрузить аватар
    </a>
    <a href=\"#\" class=\"dropdown-item\" onclick=\"return confirm('are you sure?');\">
        <i class=\"fa fa-window-close\"></i>
        Удалить
    </a>
    </div>";
}

/*
    Parameters:
        array = $user

    Description: вывести пользователя

    Return value: null
*/
function show_user($user)
{
    echo "<div class=\"col-xl-4\">
    <div id=\"c_1\" class=\"card border shadow-0 mb-g shadow-sm-hover\" data-filter-tags=\"oliver kopyov\">
        <div class=\"card-body border-faded border-top-0 border-left-0 border-right-0 rounded-top\">
            <div class=\"d-flex flex-row align-items-center\">                
                <span class=\"status status-{$user['status']} mr-3\">
                    <span class=\"rounded-circle profile-image d-block \" style=\"background-image:url('{$user['photo']}'); background-size: cover;\"></span>
                </span>
                <div class=\"info-card-text flex-1\">
                    <a href=\"javascript:void(0);\" class=\"fs-xl text-truncate text-truncate-lg text-info\" data-toggle=\"dropdown\" aria-expanded=\"false\">
                        {$user['name']}";

                $allow_to_see_options = $_SESSION['current_user']['id'] == $user['id'] || $_SESSION['current_user']['role'] == 'admin';

                if ($allow_to_see_options) {
                    show_options($user['id']);
                } else {
                    echo "</a>";
                }

                
                   echo "<span class=\"text-truncate text-truncate-xl\">{$user['job']}</span>
                </div>
                <button class=\"js-expand-btn btn btn-sm btn-default d-none\" data-toggle=\"collapse\" data-target=\"#c_1 > .card-body + .card-body\" aria-expanded=\"false\">
                    <span class=\"collapsed-hidden\">+</span>
                    <span class=\"collapsed-reveal\">-</span>
                </button>
            </div>
        </div>
        <div class=\"card-body p-0 collapse show\">
            <div class=\"p-3\">
                <a href=\"tel:+13174562564\" class=\"mt-1 d-block fs-sm fw-400 text-dark\">
                    <i class=\"fas fa-mobile-alt text-muted mr-2\"></i> {$user['phone']}</a>
                <a href=\"mailto:{$user['email']}\" class=\"mt-1 d-block fs-sm fw-400 text-dark\">
                    <i class=\"fas fa-mouse-pointer text-muted mr-2\"></i> {$user['email']}</a>
                <address class=\"fs-sm fw-400 mt-4 text-muted\">
                    <i class=\"fas fa-map-pin mr-2\"></i> {$user['address']}</address>
                <div class=\"d-flex flex-row\">
                    <a href=\"javascript:void(0);\" class=\"mr-2 fs-xxl\" style=\"color:#4680C2\">
                        <i class=\"fab fa-vk\"></i>
                    </a>
                    <a href=\"javascript:void(0);\" class=\"mr-2 fs-xxl\" style=\"color:#38A1F3\">
                        <i class=\"fab fa-telegram\"></i>
                    </a>
                    <a href=\"javascript:void(0);\" class=\"mr-2 fs-xxl\" style=\"color:#E1306C\">
                        <i class=\"fab fa-instagram\"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    </div>";
}
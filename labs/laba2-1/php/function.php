<?php

// Функция вывода меню на страницу

function main($arr_in, $g = false, $arguments = null)
{
    $g = ($g) ? 'display: inline-block;' : ''; 
    $arguments = ($arguments) ? '&arguments=' . $arguments : '';
    $s = "<ul>";
    foreach ($arr_in as $val) {
        $s .= "<li style='{$g} margin: 0 10px'><a href='{$_SERVER['SCRIPT_NAME']}?page={$val['page']}{$arguments}'>{$val['ru']}</a></li>";
    }
    $s .= "</ul>";
    return $s;
}

// Функция переадресации на главную страницу

function toMain()
{
    header("Location:{$_SERVER['SCRIPT_NAME']}?page=main");
    exit;
}

// Функция валидации URL

function validateUrl($keys, $page, $arr)
{
    // Количество передаваемых параметров не может быть больше 2-ух (только для 'page' и 'arguments')

    if (count($keys) > 2) {
        toMain();
    }

    // Переданный параметр должен быть в массиве с пунктами меню

    if (!array_key_exists($_GET['page'], $arr) || ($_GET['arguments'])
        ? (array_key_exists($page, $arr))
        ? !array_key_exists($_GET['page'], $arr[$page]['sub']) : true : '' || $_GET['page'] == $ex
    ) {
        toMain();
    }

    // Переданные ключи параметров могут быть только 'page' или 'paramets'

    foreach ($keys as $val) {
        $key = $val;
    }

    if ($key !== 'page' && $key !== 'arguments') {
        toMain();
    } 
}

function validateReg($post)
{
    $login = trim(strip_tags($post['login']));
    $psw = trim(strip_tags($post['psw']));
    $psw2 = trim(strip_tags($post['psw2']));
    $fio = trim(strip_tags($post['fio']));
    $age = trim(strip_tags($post['age']));
    $email = trim(strip_tags($post['email']));

    $_SESSION['data'] = ['login' => $login, 'fio' => $fio, 'date_born' => $date_born, 'email' => $email];

    if (checkLogin($login) == 'занят') {
        unset($_SESSION['data']['login']);
        $err[] = "К сожалению, логин: <b> {$login} </b> занят.";
    } 

    if (strlen($psw) < 8) {
        $err[] = "Пароль должен быть не менее 8 символов!";
    }

    if ($psw2 !== $psw) {
        $err[] = "Пароли не совпадают!";
    }

    if ($age) {
        $date = date_create($age);
        $now = time();
        date_add($date, date_interval_create_from_date_string('18 years'));
        $d = date_format($date, 'U');
        if ($d >= $now) {
            unset($_SESSION['data']['date']);
            $err[] = "Зарегистрироваться могут только люди, которым исполнилось 18 лет!";
        }
    }

    $_SESSION['err'] = $err;

    if ($_SESSION['err']) {
        header("Location: {$_SERVER['PHP_SELF']}?page=reg");
        exit;
    } else {
        registration($post);
    }
}

function showErrorMessage($data)
{
    $err = '<ul>'."\n";
   
    if (is_array($data)) {
        foreach ($data as $val) {
            $err .= '<li style="color:red;">'. $val .'</li>'."\n";
        }
    } else {
        $err .= '<li style="color:red;">'. $data .'</li>'."\n";
    }
   
    $err .= '</ul>'."\n";
   
    return $err;
}

function connect()
{
    require_once 'db.php';

    try {
        $link = @mysqli_connect($db['host'], $db['user'], $db['psw'], $db['dbname']);

        if (!$link) {
            throw new Exception("Error ".mysqli_connect_errno().": ".mysqli_connect_error());
        }
    } catch(Exception $e) {
        echo $e->getMessage();
        die;
    }

    return $link;
}

function checkLogin($row)
{
    $query = "SELECT login FROM users WHERE login=?";

    $arr = [
        'params' => $row,
        'type' => 's',
        'rows' => ['login']
    ];

    $res = MyQuery($query, $arr, true);

    return ($res[0] == $row) ? 'занят' : 'свободен';
}

function registration($post)
{
    $login = trim(strip_tags($post['login']));
    $psw = trim(strip_tags($post['psw']));
    $fio = trim(strip_tags($post['fio']));
    $date_born = trim(strip_tags($post['date_born']));
    $email = trim(strip_tags($post['email']));

    $psw = password_hash($psw, PASSWORD_DEFAULT);

    $query = "INSERT INTO `users` (`login`, `password`, `fio`, `date_born`, `email`) VALUES (?, ?, ?, ?, ?)";
    $params = [
        'params' => [
        $login, 
        $psw, 
        $fio, 
        $date_born, 
        $email
        ],
        'type' => 'sssss'
    ];

    if (!MyQuery($query, $params)) {
        $_SESSION['err'] = "Ошибка регистрации!";

        header("Location: {$_SERVER['PHP_SELF']}?page=reg");
        exit;
    }

    $cookie = [
        'fio' => $fio,
        'date_born' => $date_born,
        'email' => $email
    ];

    setcookie('user', base64_encode(serialize($cookie)), time() + 3600 * 24 * 30);
    unset($_SESSION['data']);
}

function MyQuery($sql, $arr = null, $answer = false)
{
    $params = $arr['params'];
    $type = $arr['type'];
    $pos = $arr['rows'];
    $row = [];
    $link =  mysqli_connect('localhost', 'root', '', 'laba1');

    $stmt = mysqli_prepare($link, $sql);

    if (is_array($params)) {
        mysqli_stmt_bind_param($stmt, $type, ...$params); 
    } else {
        mysqli_stmt_bind_param($stmt, $type, $params);
    }

    if (!empty($pos)) {
        foreach ($pos as $value) {
            $param[] = &$row[$value];
        }
        call_user_func_array(array($stmt, 'bind_result'), $param);
    }

    $res = mysqli_stmt_execute($stmt);

    if (!empty($pos)) {
        mysqli_stmt_fetch($stmt);
    }
    if ($answer) {
        $keys = '';
        foreach ($pos as $value) {
            $keys .= $value . " ";
        }

        $key = explode(' ', $keys, -1);

        $res = array_combine($key, $param);
    }
    mysqli_stmt_close($stmt);
    mysqli_close($link);

    return $res;
}

function auth($data)
{
    $arr = [
        'params' => $data['login'],
        'type' => 's',
        'rows' => [
            'login',
            'password'
        ]
        ];
    $sql = "SELECT login, password FROM users WHERE login=?";
    $user = MyQuery($sql, $arr, true);

    if ($data['login'] !== $user['login']) {
        $err[] = "Пользователь <b> {$data['login']} </b> не найден!";
    }

    if (!password_verify($data['psw'], $user['password'])) {
        $err[] = "Пароль введён неверно!";
    }

    if ($err) {
        $_SESSION['err'] = $err;

        header("Location: {$_SERVER['PHP_SELF']}?page=auth");
        exit;
    } else {
        $arr = [
            'params' => $data['login'],
            'type' => 's',
            'rows' => [
                'fio',
                'date_born',
                'email'
            ]
            ];
        $sql = "SELECT fio, date_born, email FROM users WHERE login=?";
        $auth = MyQuery($sql, $arr, true);

        $_SESSION['user'] = $auth;

        setcookie('user', base64_encode(serialize($auth)), time() + 3600 * 24 * 30);
    }
}

function logout()
{
    unset($_SESSION['user']);
    setcookie('user', '', time() - 3600);

    header("Location: {$_SERVER['PHP_SELF']}?page=main");
    exit;
}

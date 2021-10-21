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

    if (count($keys) >= 3) {
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
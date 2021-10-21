<? 
require_once 'php/array.php';
require_once 'php/function.php';



$page = $_GET['arguments'] ?? $_GET['page'];

// Выполняем проверку URL на корректность 

if ($page) {
    $keys = array_keys($_GET);
    
    validateUrl($keys, $page, $arr);
} else {
    toMain();
}

?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Лабораторная работа</title>
</head>

<body>
    <div>
            <h3>
                BroodBuy
            </h3>
            <!-- Вызываем функцию для вывода меню сайта -->
            <?= main($arr, true); ?>
            
            <div style="display: flex;">
                <? 
                    // Если есть параметр, выводим пункты вертикального меню

                    if ($page && $arr[$page]['sub']) {
                        echo main($arr[$page]['sub'], false, $page);
                    }
                ?>
            
                <?
                
                // В зависимости от переданного параметра, выводим горизонтальное / вертикальное меню

                if ($_GET['arguments']) {
                    if (is_file("html/{$arr[$page]['sub'][$_GET['page']]['html']}")) {
                        include_once "html/{$arr[$page]['sub'][$_GET['page']]['html']}";
                    }
                } elseif ($_GET['page']) {
                    if (is_file("html/{$arr[$page]['html']}")) {
                        include_once "html/{$arr[$page]['html']}";
                    }
                }
                ?>
            </div>
    </div>
</body>
</html>

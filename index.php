<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="js/jquery-3.6.0.js"></script>
    <script src="js/filter.js"></script>
</head>
<body>

<?php
require 'vendor/autoload.php';

use \PhpOffice\PhpSpreadsheet\Shared\Date;

$file = 'pricelist.xls'; // файл для получения данных
$excel = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);; // подключить Excel-файл
$excel->setActiveSheetIndex(0); // получить данные из указанного листа

$sheet = $excel->getActiveSheet();

$i = 0; // счетчик колличества операций
$j = 0; // счетчик колличества вставленных в бд позиций

$host = 'localhost';
$user = 'db';
$password = 'db';
$db_name = 'db';

$link = mysqli_connect($host, $user, $password, $db_name) or die(mysqli_error($link)); //устанавливаем соединение и
// 'or die' - это вывод ошибок sql
mysqli_query($link, "SET NAMES 'utf8'"); // задаем кодировку(без этой строчки кирилица плохо отображается)


/*
НАХОДИМ САМЫЙ ДОРОГОЙ И САМЫЙ ДЕШЕВЫЙ ТОВАР и присваиваем их переменным для дальнейших манипуляций
6. Выделить в таблице красным цветом самый дорогой товар (по рознице) single_cost
7. Выделить в таблице зеленым цветом самый дешевый товар (по опту) wholesale_cost
*/
$query_max = "SELECT MAX(single_cost) as max  FROM `test`";
$result1 = mysqli_query($link, $query_max);
for ($data1 = []; $row = mysqli_fetch_assoc($result1); $data1[] = $row) ;
$result_max = (int)$data1[0]['max'];

$query_min = "SELECT MIN(wholesale_cost) as min FROM `test`";
$result2 = mysqli_query($link, $query_min);
for ($data2 = []; $row = mysqli_fetch_assoc($result2); $data2[] = $row) ;
$result_min = $data2[0]['min'];


// формирование html-кода таблицы с данными
$html = '<table border="1" cellpadding="0" cellspacing="0">';

$html .= '<tr>';
$html .= '<th>' . 'Наименование товара' . '</th>';
$html .= '<th>' . 'Стоимость, руб' . '</th>';
$html .= '<th>' . 'Стоимость опт, руб' . '</th>';
$html .= '<th>' . 'Наличие на складе 1, шт' . '</th>';
$html .= '<th>' . 'Наличие на складе 2, шт' . '</th>';
$html .= '<th>' . 'Страна производства' . '</th>';
$html .= '<th>' . 'Примечание' . '</th>';
$html .= '<tr>';

foreach ($sheet->getRowIterator() as $row) {
    $i++;
    if (((string)$sheet->getCell("B$i") === 'Стоимость, руб') or
        ((string)$sheet->getCell("B$i") === 'Стоимость')) {
        continue;
    } else {
        $j++;
    }

    $cellIterator = $row->getCellIterator();
    $single_cost = (float)(string)$sheet->getCell("B$i");
    $Name_of_product = (string)$sheet->getCell("A$i");
    $wholesale_cost = (int)(string)$sheet->getCell("C$i");
    $In_stock_1 = (int)(string)$sheet->getCell("D$i");
    $In_stock_2 = (int)(string)$sheet->getCell("E$i");
    $Country = (string)$sheet->getCell("F$i");
    $Comment = '';
    if ($In_stock_1 < 20 or $In_stock_2 < 20) {
        $Comment = "Осталось мало!! Срочно докупите!!!";
    }

    /* ФОРМИРУЕМ УСЛОВИЕ ДЛЯ:
    6. Выделить в таблице красным цветом самый дорогой товар (по рознице)
    7. Выделить в таблице зеленым цветом самый дешевый товар (по опту)
    */
    if ($result_max == (int)$single_cost) {
        $html .= '<tr bgcolor="#dc143c">';
    } elseif ($result_min == $wholesale_cost) {
        $html .= '<tr bgcolor="#006400">';
    } else {
        $html .= '<tr>';
    }
    $html .= '<td>' . $Name_of_product . '</td>';
    $html .= '<td>' . $single_cost . '</td>';
    $html .= '<td>' . $wholesale_cost . '</td>';
    $html .= '<td>' . $In_stock_1 . '</td>';
    $html .= '<td>' . $In_stock_2 . '</td>';
    $html .= '<td>' . $Country . '</td>';
    $html .= '<td>' . $Comment . '</td>';
    $html .= '<tr>';

    $result_stock_1 += $In_stock_1;  // считаю общее колличество товаров на складах 1 и 2
    $result_stock_2 += $In_stock_2;

    $result_single += $single_cost;  // общая стоимость всех товаров розничной цены

    $result_wholesale += $wholesale_cost;  // общая стоимость всех товаров оптовой цены


    // формируем запрос к БД для заполнения полей
    /*$query = "
        INSERT INTO `test` (
            `Name_of_product`,
            `single_cost`,
            `wholesale_cost`,
            `In_stock_1`,
            `In_stock_2`,
            `Country`,
            `Comment`
        )
        VALUES (
            '$Name_of_product',
            $single_cost,
            $wholesale_cost,
            $In_stock_1,
            $In_stock_2,
            '$Country',
            '$Comment'
        )";*/
    //mysqli_query($link, $query) or die(mysqli_error($link));

}
$html .= '</table>';

mysqli_close($link); // закрытие соединения

//echo (int)$data1[0]['max'], '<br>';
//echo $data2[0]['min'], '<br>';

require_once 'test.php';

// вывод данных
echo $html;
echo '<br><br>';

// 3. Вывести под таблицей общее количество товаров на Складе1 и на Складе2
echo 'общее количество товаров на Складе1: ' . $result_stock_1 . '<br>';
echo 'общее количество товаров на Складе2: ' . $result_stock_2 . '<br><br>';

// 4. Вывести под таблицей среднюю стоимость розничной цены товара
$single_average_cost = intdiv($result_single, $j);  // средняя стоимость розничной цены товара
echo 'средняя стоимость розничной цены товара: ' . $single_average_cost . '<br>';

// 5. Вывести под таблицей среднюю стоимость оптовой цены товара
$wholesale_average_cost = intdiv($result_wholesale, $j);  // средняя стоимость розничной цены товара
echo 'средняя стоимость оптовой цены товара: ' . $wholesale_average_cost . '<br>';

?>

</body>
</html>


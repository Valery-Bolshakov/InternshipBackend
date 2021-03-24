<?php
require 'vendor/autoload.php';

use \PhpOffice\PhpSpreadsheet\Shared\Date;

$file = 'pricelist.xls'; // файл для получения данных
$excel = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);; // подключить Excel-файл
$excel->setActiveSheetIndex(0); // получить данные из указанного листа

$sheet = $excel->getActiveSheet();
$i = 0;

$host = 'localhost';
$user = 'db';
$password = 'db';
$db_name = 'db';

$link = mysqli_connect($host, $user, $password, $db_name) or die(mysqli_error($link)); //устанавливаем соединение и
// 'or die' - это вывод ошибок sql
mysqli_query($link, "SET NAMES 'utf8'"); // задаем кодировку(без этой строчки кирилица плохо отображается)

//mysqli_close($link); // закрытие соединения


// формирование html-кода с данными
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
    }
    $html .= '<tr>';
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

    $html .= '<td>' . $Name_of_product . '</td>';
    $html .= '<td>' . $single_cost . '</td>';
    $html .= '<td>' . $wholesale_cost . '</td>';
    $html .= '<td>' . $In_stock_1 . '</td>';
    $html .= '<td>' . $In_stock_2 . '</td>';
    $html .= '<td>' . $Country . '</td>';
    $html .= '<td>' . $Comment . '</td>';


    $query = "
        INSERT INTO test (
            Name_of_product, 
            single_cost, 
            wholesale_cost, 
            In_stock_1, 
            In_stock_2, 
            Country, 
            Comment
        ) 
        VALUES (
            '$Name_of_product', 
            $single_cost, 
            $wholesale_cost, 
            $In_stock_1, 
            $In_stock_2, 
            '$Country', 
            '$Comment'
        )";

    //$result = mysqli_query($link, $query) or die(mysqli_error($link));  // формируем запрос к БД

//    foreach ($cellIterator as $cell) {
//
//        // значение текущей ячейки
//        $value = $cell->getCalculatedValue();
//
//        // если дата, то преобразовать в формат PHP
//        if (Date::isDateTime($cell)) {
//            $value = date('d.m.Y', Date::excelToTimestamp($cell->getValue()));
//        }
//
//        $html .= '<td>'.$value.'</td>';
//    }
    $html .= '<tr>';
}
$html .= '</table>';

mysqli_close($link); // закрытие соединения

// вывод данных
echo $html;

echo '<br><br> Hello';




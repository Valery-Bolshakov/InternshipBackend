<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<form action="" method="POST">
    <span>Показать товары, у которых </span>
    <select id="u1_input">
        <option value="Розничная цена">Розничная цена</option>
        <option value="Оптовая цена">Оптовая цена</option>
    </select>

    <span>от</span>

    <input id="u3_input" type="text" value="1000">

    <span>до</span>

    <input id="u5_input" type="text" value="3000">

    <span>рублей, и на складе </span>

    <select id="u7_input">
        <option value="Более">Более</option>
        <option value="Менее">Менее</option>
    </select>

    <input id="u9_input" type="text" value="20">

    <span>штук.</span>

    <input type="submit" name="submit" value="ПОКАЗАТЬ ТОВАРЫ">

    <p><span>ЗДЕСЬ ВАША ТАБЛИЦА ИЗ ПЕРВОГО МОДУЛЯ, </span></p>
    <p><span>ДАННЫЕ В КОТОРОЙ ОБНОВЛЯЮТСЯ БЕЗ ПЕРЕЗАГРУЗКИ СТРАНИЦЫ </span></p>
    <p><span>В СООТВЕТСТВИИ С ТРЕБОВАНИЯМИ ФИЛЬТРА</span></p>

</form>

</body>
</html>
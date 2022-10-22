<?php

function getCroppedText(string $fullText, int $length = 200): string
{
    return mb_substr($fullText, 0, $length) . "...";
}

function getPreview(string $previewText, string $link): string
{
    $words = explode(' ', $previewText);
    $linkText = implode(' ', array_slice($words, count($words) - 3));

    $preview = implode(' ', array_slice($words, 0, count($words) - 3));
    $preview .= " <a href=\"$link\">$linkText</a>";

    return $preview;
}

$articleText = file_get_contents('article.txt');
$articleLink = "https://clck.ru/32RRbN";
$articlePreview = getPreview(getCroppedText($articleText), $articleLink);

?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Тестовое задание №1</title>
    <style>
        body {
            min-height: 80vh;
            display: flex;
            flex-direction: column;
        }
        main {
            width: 576px;
            margin: auto;
        }
        h1 {
            font-size: 24px;
        }
    </style>
</head>
<body>
<main>
    <h1>УРОБОРОС. Знак постоянных ПЕРЕМЕН несменяемого порядка.</h1>
    <p><?= $articlePreview ?></p>
</main>
</body>
</html>

<!--
    Для удобной работы с кириллицей в кодировке utf-8 необходимо подключить модуль mbstring.
    В корень с установленным php я добавил файл с настройками php.ini. Исходное содержимое
    файла скопировал из php.ini-development, который уже присутствовал в папке после установки.
    Далее, для подключения модуля mbstring раскомментировал строки:
        extension_dir = "ext"
        extension=mbstring
    Названия функции данного модуля начитаются с префикса "mb_". В частности используется
    функция mb_substr() для получения подстроки заданной длины.

    Значение переменной $articleText изначально находилось в index.php,
    но для удобства я перенес его в article.txt. Хранить статью
    с длинными строками в коде неудобно. А любое дополнительное форматирование
    (переносы, отступы) добавляет в статью лишние символы,
    которые влияют на вычисление $articlePreview.
-->
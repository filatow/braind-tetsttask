<?php

const NUM_KEY = 10;

/* Добавляет число на соответствующую позицию в многомерном массиве.
 * Индекс последнего уровня для числа всегда равен 10.
 * Из значений остальных впереди идущих индексов
 * можно собрать данное число. Например:
 * $array[0][10] == 0; // true
 * $array[1][10] == 1; // true
 * $array[1][2][10] == 12; // true
 * $array[3][5][7][10] == 357; // true
 * */
function growBranch(array &$arr, array $keys, int $val): void
{
    $key = array_shift($keys);

    if (!empty($keys)) {
        growBranch($arr[$key], $keys, $val);
    }

    if (empty($arr[$key][NUM_KEY])) {
        $arr[$key][NUM_KEY] = $val;
    }
}

/* Делает многомерный массив "плоским", одномерным,
 * с погружением на всю глубину.
 * */
function flattenArray(array $arr): array
{
    $flatArray = [];

    foreach ($arr as $key => $val) {
        if (is_array($val)) {
            $flatArray = array_merge($flatArray, flattenArray($val));
        } else {
            $flatArray[$key] = $val;
        }
    }

    return $flatArray;
}

/* На основе заданного числа строит многомерный массив
 * по определенным правилам.
 * В результате для $num = 11 получится массив вида -
 * https://disk.yandex.ru/i/yGmznOlA_M5z_A
 * */
function constructMultiArray(int $num): array
{
    $multiArray = [];

    for ($current = 0; $current <= $num; $current++) {
        $digits = str_split(strval($current));
        growBranch($multiArray, $digits, $current);
    }

    return $multiArray;
}

/* Возвращает порядковый номер числа k
 * в последовательности n чисел
 * согласно правилам "странной математики"
 * */
function findPosition(int $n, int $k): int
{
    if ($k > $n) return -1;

    $multiArray = constructMultiArray($n);
    $flatArray = flattenArray($multiArray);

    return array_search($k, $flatArray);
}

?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Тестовое задание №3</title>
    <style>
        p {
            font-family: monospace;
            font-size: 18px;
        }
    </style>
</head>
<body>
<main>
    <h1>Задание 3. Помочь ученикам в изучении странной математики</h1>
    <p>n=9, k=5: <?= findPosition(9, 5) ?></p>
    <p>n=5, k=9: <?= findPosition(5, 9) ?></p>
    <p>n=11, k=2: <?= findPosition(11, 2) ?></p>
    <p>n=20, k=19: <?= findPosition(20, 19) ?></p>
</main>
</body>
</html>

<!--
    С помощью встроенной функции sort() задача решается просто и быстро:
    function findPositions(int $n, int $k): int
    {
        $arr = [];
        for ($i = 0; $i <= $n; $i++) {
            $arr[] = $i;
        }
        sort($arr, SORT_STRING);
        return array_search($k, $arr);
    }
    Однако, по условиям задачи использовать
    встроенные функции сортировки нельзя.
    Значит, основная суть задачи состоит в написании
    собственного алгоритма.

    Я сейчас как раз читаю "Грокаем алгоритмы". Наверное,
    надо было написать собственную реализацию одного из известных
    и проверенных алгоритмов сортировки - подумал я. Впрочем,
    можно было найти их готовые реализации на PHP.
    Но тут я решил, что все-таки хочу попробовать
    написать что-то своё ("велосипед").

    В "Грокаем алгоритмы" много говорится о нотации определения
    скорости работы алгоритма "O-большое". Согласно этой нотации,
    списки быстро работают на вставку новых элементов - О(1),
    в то же время для массивов этот показатель хуже - О(n).
    А для моей реализации, как я её видел, нужно было,
    как раз, много вставлять ($n раз) и один раз считать.

    В стандартной библиотеке php я нашел реализацию двусвязного списка -
    класс SplDoublyLinkedList. Пробовал решить задачу с его помощью, но
    в какой-то момент процесс забуксовал... И я все-таки вернулся
    к массивам, где добился успеха.

    Из любопытства сделал замеры скорости работы. Оказалось,
    мой алгоритм работает приблизительно в 3 раза медленнее,
    чем используемый в стандартной функции sort() -
    на скриншотах 1-ое число - время выполнения 25-и итераций,
    2-ое - среднее значение, n = 300_000, k = 150_000:
        1) sort() - https://disk.yandex.ru/i/yvMKinjz9hv0ow
        2) собственный алгоритм - https://disk.yandex.ru/i/Af1QUnJ-jWSA6A
    Более того, мой алгоритм также более требовательный по памяти:
    При значении n = 1_000_000 мой алгоритм выдаёт ошибку
    "Fatal error: Allowed memory size of 134217728 bytes exhausted",
    в то время как sort() продолжает работать.
-->

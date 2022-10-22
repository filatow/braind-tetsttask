<?php

function calculateCommitAmountToResolve(int $N, int $M): int
{
    $calculating = true;
    $commitAmount = 0;

    $fixOneWarning = function (int $n, int $m) use (&$commitAmount): array {
        $commitAmount++;
        return [$n, $m + 1];
    };

    if ($N < 0 || $N > 1000 || $M < 0 || $M > 1000) {
        echo "<span style='background-color: #92001e; color: white;'>
            Должно выполняться условие: 0 ≤ N, M ≤ 1000.</span> ";
        return -1;
    }

    do {
        /* Отсутствие ворнингов лишает пространства для манёвра:
         * фатальные ошибки мы можем убирать только в связке по 2. Если
         * число ошибок нечетное, то одна ошибка всегда будет оставаться.
         * */
        if ($M === 0 && $N % 2 !== 0) {
            $commitAmount = -1;
            break;
        }
        /* Число ворнингов сокращается, только если исправлять 2 за раз.
         * Поэтому сначала получим их в четном количестве. Согласно заданию,
         * исправление одного ворнинга по итогу увеличивает их число на 1.
         * */
        if ($M % 2 !== 0) {
            list($N, $M) = $fixOneWarning($N, $M);
            continue;
        }
        /* Проверяем условие, при котором решение может быть вычислено
         * незамедлительно. Если true: получаем итоговый результат
         * и выходим из цикла, иначе увеличиваем число ворнингов.
         * */
        if (($N + $M / 2) % 2 === 0) {
            $commitAmount += ($M / 2) + ($N + ($M / 2)) / 2;
            $calculating = false;
        } else {
            list($N, $M) = $fixOneWarning($N, $M);
        }

        /* Можно не использовать переменную $calculating - для этого:
         * цикл сделать бесконечным while (true) и выходить по условию через break.
         * Но с переменной код кажется чуь более читабельным.
         * */
    } while ($calculating);

    return $commitAmount;
}

// Вариант через рекурсию
function calculateCommitAmountToResolveR(int $N, int $M): int
{
    if ($N < 0 || $N > 1000 || $M < 0 || $M > 1000) {
        echo "<span style='background-color: #92001e; color: white;'>
            Должно выполняться условие: 0 ≤ N, M ≤ 1000.</span> ";
        return -1;
    }

    if ($M === 0 && $N % 2 !== 0) {
        return -1;
    }

    if ($M % 2 !== 0) {
        return 1 + calculateCommitAmountToResolveR($N, $M + 1);
    }

    if (($N + $M / 2) % 2 === 0) {
        return ($M / 2) + ($N + ($M / 2)) / 2;
    } else {
        return 1 + calculateCommitAmountToResolveR($N, $M + 1);
    }
}

?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Тестовое задание №2</title>
    <style>
        p {
            font-family: monospace;
            font-size: 18px;
        }
    </style>
</head>
<body>
<main>
    <h1>Задание 2. Помочь программисту Пете победить эрроры и ворнинги</h1>
    <h2>Вариант решения с циклом</h2>
    <p>N=3, M=3: <?= calculateCommitAmountToResolve(3, 3) ?></p>
    <p>N=1, M=0: <?= calculateCommitAmountToResolve(1, 0) ?></p>
    <p>N=0, M=1: <?= calculateCommitAmountToResolve(0, 1) ?></p>
    <p>N=8, M=0: <?= calculateCommitAmountToResolve(8, 0) ?></p>
    <p>N=6, M=1: <?= calculateCommitAmountToResolve(6, 1) ?></p>
    <p>N=2, M=1004: <?= calculateCommitAmountToResolve(2, 1004) ?></p>
    <p>N=-2, M=4: <?= calculateCommitAmountToResolve(-2, 4) ?></p>
    <h2>Вариант через рекурсию</h2>
    <p>N=3, M=3: <?= calculateCommitAmountToResolveR(3, 3) ?></p>
    <p>N=1, M=0: <?= calculateCommitAmountToResolveR(1, 0) ?></p>
    <p>N=0, M=1: <?= calculateCommitAmountToResolveR(0, 1) ?></p>
    <p>N=8, M=0: <?= calculateCommitAmountToResolveR(8, 0) ?></p>
    <p>N=6, M=1: <?= calculateCommitAmountToResolveR(6, 1) ?></p>
    <p>N=2, M=1004: <?= calculateCommitAmountToResolveR(2, 1004) ?></p>
    <p>N=-2, M=4: <?= calculateCommitAmountToResolveR(-2, 4) ?></p>
</main>
</body>
</html>

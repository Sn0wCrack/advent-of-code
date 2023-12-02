<?php

$lines = file('part_01_input.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$total = 0;

foreach ($lines as $line) {
    if (preg_match('/Game (?P<id>\d+): (?P<sets>.*)/', $line, $lineMatches) === false) {
        continue;
    }

    $id = (int) $lineMatches['id'];

    $sets = explode('; ', $lineMatches['sets']);

    $minimums = [
        'red' => 0,
        'green' => 0,
        'blue' => 0,
    ];

    foreach ($sets as $set) {
        if (preg_match_all('/(?P<amount>\d+) (?P<colour>blue|red|green)/', $set, $setMatches, PREG_SET_ORDER) === false) {
            continue;
        }

        foreach ($setMatches as $setMatch) {
            $amount = (int) $setMatch['amount'];

            if ($amount > $minimums[$setMatch['colour']]) {
                $minimums[$setMatch['colour']] = $amount;
            }
        }
    }

    $total += $minimums['red'] * $minimums['green'] * $minimums['blue'];
}

echo "{$total}\n";

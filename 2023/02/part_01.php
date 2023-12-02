<?php

$maximums = [
    'red' => 12,
    'green' => 13,
    'blue' => 14,
];

$lines = file('part_01_input.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$total = 0;

foreach ($lines as $line) {
    if (preg_match('/Game (?P<id>\d+): (?P<sets>.*)/', $line, $lineMatches) === false) {
        continue;
    }

    $possible = true;

    $id = (int) $lineMatches['id'];

    $sets = explode('; ', $lineMatches['sets']);

    foreach ($sets as $set) {
        if (preg_match_all('/(?P<amount>\d+) (?P<colour>blue|red|green)/', $set, $setMatches, PREG_SET_ORDER) === false) {
            continue;
        }

        foreach ($setMatches as $setMatch) {
            $amount = (int) $setMatch['amount'];

            if ($amount > $maximums[$setMatch['colour']]) {
                $possible = false;
            }
        }
    }

    if (!$possible) {
        echo "{$id} is not possible\n";
        continue;
    }

    echo "{$id} is possible\n";

    $total += $id;
}

echo "{$total}\n";

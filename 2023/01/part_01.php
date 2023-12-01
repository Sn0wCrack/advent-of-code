<?php

$lines = file('part_01_input.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$total = 0;

foreach ($lines as $index => $line) {
    if (preg_match_all('/\d/', $line, $digits) === false) {
        continue;
    }

    // 0th entry is matches, since I'm not bothering to capture anything.
    $first = reset($digits[0]);
    $last = end($digits[0]);

    $number = (int) "{$first}{$last}";

    $total += $number;
}

echo "{$total}\n";
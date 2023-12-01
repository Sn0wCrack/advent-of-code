<?php

$lines = file('part_01_input.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$mapping = [
    'zero' => 0,
    'one' => 1,
    'two' => 2,
    'three' => 3,
    'four' => 4,
    'five' => 5,
    'six' => 6,
    'seven' => 7,
    'eight' => 8,
    'nine' => 9,
];

$total = 0;

foreach ($lines as $line) {
    // Uses a positive lookahead so it can read values like "eighthree" or "sevenine" which seem to be valid in this case, something not mentioned in the examples.
    if (preg_match_all('/(?=(\d|zero|one|two|three|four|five|six|seven|eight|nine))/i', $line, $digits) === false) {
        continue;
    }

    // 0th entry is matches, 1st is groups
    $first = strtolower(reset($digits[1]));
    $last = strtolower(end($digits[1]));

    // If the value isn't in our mappings its a safe bet its a digit
    $first = $mapping[$first] ?? $first;
    $last = $mapping[$last] ?? $last;

    $number = (int) "{$first}{$last}";

    $total += $number;
}

echo "{$total}\n";
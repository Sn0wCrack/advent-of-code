<?php

$lines = file('part_01_input.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$total = 0;

foreach ($lines as $line) {
    if (preg_match('/Card\s+(?P<id>\d+): (?P<sets>.*)/', $line, $lineMatches) === false) {
        continue;
    }

    $cardNumber = $lineMatches['id'];

    $sets = explode(' | ', $lineMatches['sets']);

    $winningNumbers = array_filter(explode(' ', $sets[0]));
    $selectedNumbers = array_filter(explode(' ', $sets[1]));

    $multiplier = 1;
    $matches = 0;
    $points = 0;

    foreach($selectedNumbers as $selectedNumber) {
        foreach ($winningNumbers as $winningNumber) {
            if ($selectedNumber !== $winningNumber) {
                continue;
            }

            $points += $multiplier;
            $matches += 1;

            if ($matches > 1) {
                $multiplier *= 2;
            }
        }
    }

    if ($points === 0) {
        continue;
    }

    $total += $points;
}

echo "{$total}\n";

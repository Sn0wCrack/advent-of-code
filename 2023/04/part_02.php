<?php

$lines = file('part_01_input.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$total = 0;

$cards = [];

foreach ($lines as $line) {
    if (preg_match('/Card\s+(?P<id>\d+): (?P<sets>.*)/', $line, $lineMatches) === false) {
        continue;
    }

    $cardNumber = $lineMatches['id'];

    $sets = explode(' | ', $lineMatches['sets']);

    $winningNumbers = array_filter(explode(' ', $sets[0]));
    $selectedNumbers = array_filter(explode(' ', $sets[1]));

    $cards[$cardNumber] = [
        'winning' => $winningNumbers,
        'selected' => $selectedNumbers,
    ];
}

$total = count($cards);

function processCard(array $card): int {
    $numberOfMatches = 0;

    foreach ($card['selected'] as $selectedNumber) {
        foreach ($card['winning'] as $winningNumber) {
            if ($selectedNumber !== $winningNumber) {
                continue;
            }

            $numberOfMatches += 1;
        }
    }

    return $numberOfMatches;
}

function processAdditionalCards(array $cards, int $number, int $matches): int {
    $start = $number + 1;
    $end = min($number + $matches, count($cards));

    $total = $matches;

    for ($i = $start; $i <= $end; ++$i) {
        $numberOfMatches = processCard($cards[$i]);

        $total += processAdditionalCards($cards, $i, $numberOfMatches);
    }

    return $total;
}

foreach ($cards as $number => $card) {
    $numberOfMatches = processCard($card);

    $total += processAdditionalCards($cards, $number, $numberOfMatches);
}

echo "{$total}\n";
